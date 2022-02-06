<template>
	<Login v-if="!authenticated" v-on:authenticated="setAuthenticated" />
	<div id="chat_container" class="row" v-else>
		<Channels
			:channels="channels"
			:active_channel="active_channel ? active_channel.uniqueName : ''"
			v-on:show-message="showMessages"
			v-on:new-channel="addChannel"
		/>
		<Messages
			v-on:new-message="addMessage"
			v-on:log-out="logOut"
			:is-loading="isLoading"
			:channel="active_channel ? active_channel.uniqueName : ''"
			:messages="messages"
			:userData="userData"
		/>
	</div>
</template>

<script>
import Login from "./Login";
import Messages from "./Messages";
import Channels from "./Channels";
const Chat = require("twilio-chat");

let chatChannel;
let Client;

export default {
	components: {
		Login,
		Channels,
		Messages,
	},
	data() {
		return {
			authenticated: false,
			messages: [],
			userData: {},
			token: null,
			channels: [],
			active_channel: null,
			isLoading: false,
		};
	},
	methods: {
		setAuthenticated(userData) {
			fetch("/token", {
				method: "POST",
				body: "email=" + userData.username,
				headers: { "Content-Type": "application/x-www-form-urlencoded" },
			})
				.then((response) => response.json())
				.then((response) => {
					if (response.status == "success") {
						this.token = response.token;
						this.userData = userData;
						this.authenticated = true;

						this.initializeChat();
					}
				});
		},
		initializeChat() {
			Chat.Client.create(this.token).then((client) => {
				client.getPublicChannelDescriptors().then((channels) => {
					this.channels = channels.state.items;
				});

				Client = client;
			});
		},
		setupChannel(channel) {
			for (const [key, member] of channel.members) {
				if (member.identity == this.userData.username) {
					member.remove().then(() => {
						channel.removeAllListeners(this.joinChannel(channel));
					});
					return;
				}
			}
			channel.removeAllListeners(this.joinChannel(channel));
		},
		joinChannel(channel) {
			channel
				.join()
				.then((channel) => {
					chatChannel = channel; // Set it global
					channel.getMessages().then((messages) => {
						this.messages = messages.items;
					});

					// Listen for new messages sent to the channel
					channel.on("messageAdded", (message) => {
						this.messages.push(message.state);
					});

					this.isLoading = false;
				})
				.catch((err) => {
					console.error(err);
					// If there is error joining the room,
					// get all messages on the channel
					channel.getMessages().then((messages) => {
						this.messages = messages.items;
					});

					this.isLoading = false;
				});
		},
		addMessage(message) {
			if (chatChannel) {
				chatChannel.sendMessage(message);
			}
		},
		addChannel(uniqueName) {
			this.isLoading = true;
			Client.createChannel({
				uniqueName: uniqueName,
			})
				.then((channel) => {
					this.channels.push(channel);
					return channel;
				})
				.then((channel) => {
					this.showMessages(channel);
				});
		},
		showMessages(channel) {
			this.isLoading = true;
			this.messages = [];
			this.active_channel = null;

			Client.getChannelByUniqueName(channel.uniqueName).then((channel) => {
				this.active_channel = channel;
				this.setupChannel(channel);
			});
		},
		logOut() {
			this.userData = {};
			this.messages = [];
			this.channels = [];
			this.active_channel = null;
			this.authenticated = false;
		},
	},
};
</script>
