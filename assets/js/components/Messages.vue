<template>
	<div class="col-sm-9" v-if="channel">
		<div class="alert alert-warning" v-if="isLoading">
			Chargement en cours...
		</div>

		<div id="chatroom" v-else>
			<div id="chatroom-header">
				<h3>
					<span v-if="channel">Salon : {{ channel }}</span>
				</h3>
				<div>
					Connecté en tant que <b>{{ userData.username }}</b>
					<i id="logout-icon" class="fas fa-times" @click="logOut"></i>
				</div>
			</div>

			<div id="message-container">
				<div
					class="messages"
					:class="[message.author == userData.username ? 'right' : 'left']"
					v-for="message in messages"
					:key="message.id"
				>
					<div class="message-data">
						<div
							class="message-author"
							v-if="message.author != userData.username"
						>
							{{ message.author }}
						</div>
						<div class="message-content">{{ message.body }}</div>
					</div>
				</div>
			</div>

			<div id="chatroom-footer" class="row">
				<div class="col-sm-12">
					<div class="input-container">
						<input
							class="form-control"
							type="text"
							placeholder="Votre message"
							v-model="message"
							v-on:keyup.enter="addMessage"
						/>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	props: {
		messages: Array,
		userData: Object,
		channel: null,
		isLoading: Boolean,
	},
	data() {
		return {
			message: "",
		};
	},
	watch: {},
	methods: {
		addMessage() {
			this.$emit("new-message", this.message);
			this.message = "";
		},
		logOut() {
			this.$emit("log-out");
		},
	},
};
</script>
