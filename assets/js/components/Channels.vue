<template>
	<div class="col-sm-3" id="channels_container">
		<h3>Salons</h3>
		<ul id="list_channels">
			<li
				class="channels"
				v-for="channel in channels"
				:key="channel.sid"
				@click="showMessages(channel)"
				v-bind:class="[selected_channel == channel.uniqueName ? 'active' : '']"
			>
				<i class="fas fa-comments channel-icon"></i>
				<div class="meta">
					<p class="name">{{ channel.uniqueName }}</p>
				</div>
			</li>
		</ul>

		<div class="row">
			<div id="bottom-bar" class="col-sm-12">
				<input
					class="form-control col-sm-12"
					type="text"
					placeholder="Ajouter un salon"
					v-model="channel_name"
					v-on:keyup.enter="addChannel"
				/>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	props: {
		channels: Array,
		active_channel: null,
	},
	data() {
		return {
			channel_name: "",
			selected_channel: null,
		};
	},
	watch: {
		active_channel(value) {
			this.selected_channel = value;
		},
	},
	methods: {
		addChannel() {
			this.$emit("new-channel", this.channel_name);
			this.channel_name = "";
		},
		showMessages(channel) {
			this.selected_channel = channel.uniqueName;
			this.$emit("show-message", channel);
		},
	},
};
</script>
