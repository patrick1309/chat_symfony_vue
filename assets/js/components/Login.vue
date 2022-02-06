<template>
	<div>
		<div class="sidenav">
			<div class="login-main-text">
				<h2>Chat Application</h2>
				<p>Symfony, Vue, Twilio</p>
			</div>
		</div>
		<div class="main">
			<div class="col-md-6 col-sm-12">
				<div class="login-form">
					<form @submit="login">
						<div class="form-group">
							<label>Votre pseudo</label>
							<input
								type="text"
								class="form-control"
								v-model="username"
								v-on:keyup.enter="login"
							/>
						</div>
						<div class="alert alert-danger" v-if="message">
							{{ message }}
						</div>
						<button type="submit" class="btn btn-black" v-on:click="login">
							Se connecter
						</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	data: function () {
		return {
			username: "",
			message: "",
		};
	},
	methods: {
		login: function (e) {
			e.preventDefault();
			// Do your real authentication here...
			if (this.username) {
				this.$emit("authenticated", { username: this.username });
			} else {
				this.message = "Veuillez indiquer un pseudo";

				// clear the message after 2 sec.
				setTimeout(() => {
					this.message = "";
				}, 3000);
			}
		},
	},
};
</script>
