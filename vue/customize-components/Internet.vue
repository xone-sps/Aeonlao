<template>
<div>
	<div class="off-line" v-if="!state.online">
		<div class="off-line-msg">
			<div class="msg">Make sure your device has an active Internet connection</div>
		</div>
	</div>
</div>
	
</template>
<script>
export default{
	data(){
		return {
			state: { online: navigator.onLine }
		}
	},
	methods: {
		updateStatus(){
			this.state.online = navigator.onLine || false;
			this.$emit('detected', this.state.online)
		}
	},
	mounted(){
		window.addEventListener('load', (event)=> {
			this.updateStatus()
			window.addEventListener('online', this.updateStatus)
			window.addEventListener('offline', this.updateStatus)
		})
	},
	beforeDestroy: function(){
		window.removeEventListener('online',this.updateStatus)
		window.removeEventListener('offline', this.updateStatus)
	}
}	
</script>
<style scoped>
	.msg{
		font-size: 14px;
    	line-height: 20px;
    	color: #4b4b4b;
	}
	.off-line-msg{
		position: fixed;
    	top: 0;
    	background: #FED859;
   	 	width: 100%;
    	text-align: center;
    	z-index: 1000;
    	white-space: normal;
    	box-shadow: 0 0.5rem 1rem 0 rgba(10, 10, 10, .2);
    	-webkit-box-shadow: 0 0.5rem 1rem 0 rgba(10, 10, 10, .2);
	}
</style>