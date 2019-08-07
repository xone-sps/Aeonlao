<template>
<div unselectable="on" class="form-group" @click="SelectClick">
	<div class="input-container icon-select c">
		<input @click="showSelect" readonly="true" type="text" :placeholder="pl" spellcheck="false" maxlength="160" autocomplete="off" class="my-input form-control no-margin-bottom" v-model="selectedData.name">
		<i  @click="showSelect" class="fas fa-chevron-circle-down icon-file"></i>
		<div class="select-drop-down admin s" v-if="isShowSelect">
		    <div class="inner-input">
				<input type="text" placeholder="Search" spellcheck="false" maxlength="160" autocomplete="off" class="my-input form-control no-margin-bottom" v-model="searchBy" @keyup.enter="addSelect" @keyup="selectByUpDown">
				<svg aria-hidden="true" data-prefix="far" data-icon="search" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="icon-file select-ico"><path fill="currentColor" d="M508.5 468.9L387.1 347.5c-2.3-2.3-5.3-3.5-8.5-3.5h-13.2c31.5-36.5 50.6-84 50.6-136C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c52 0 99.5-19.1 136-50.6v13.2c0 3.2 1.3 6.2 3.5 8.5l121.4 121.4c4.7 4.7 12.3 4.7 17 0l22.6-22.6c4.7-4.7 4.7-12.3 0-17zM208 368c-88.4 0-160-71.6-160-160S119.6 48 208 48s160 71.6 160 160-71.6 160-160 160z"></path></svg>
			</div>
			<div class="drops" :class="filterData.length>=5 ? 'is-5' : ''">
				<a v-for="(c, index) in filterData" :key="index" @click="selected(c)" class="navbar-item navbar-item-space navbar-item-unnormal admin" :class="[ activeClass(c), multipleActive(c) ]"> <span>{{ c.name }} </span>
            		<svg v-if="multiple && matchDefault(c.id)" class="un-select" aria-hidden="true" data-prefix="fal" data-icon="times" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
            			<path fill="currentColor" d="M217.5 256l137.2-137.2c4.7-4.7 4.7-12.3 0-17l-8.5-8.5c-4.7-4.7-12.3-4.7-17 0L192 230.5 54.8 93.4c-4.7-4.7-12.3-4.7-17 0l-8.5 8.5c-4.7 4.7-4.7 12.3 0 17L166.5 256 29.4 393.2c-4.7 4.7-4.7 12.3 0 17l8.5 8.5c4.7 4.7 12.3 4.7 17 0L192 281.5l137.2 137.2c4.7 4.7 12.3 4.7 17 0l8.5-8.5c4.7-4.7 4.7-12.3 0-17L217.5 256z"></path>
            		</svg>
            	</a> 
			</div>
            <a v-if="filterData.length===0" @click="addSelect" class="navbar-item navbar-item-space navbar-item-unnormal admin">
            	<span>{{ !multiple ? "No results match" : "Enter to add" }} "{{ searchBy }}"</span>
            </a> 
        </div>
	</div>
</div>
</template>
<script>
import {mapState, mapGetters, mapActions, mapMutations} from 'vuex'

export default{
	props:{
      data: { type: Array, default: function(){  return [] } },
      multiple:{ type: Boolean, default: false},
      default: {type: Array, default: function(){  return [] } },
      pl: {type: String, default: ''}
    },
	data(){
		return {
			isShowSelect: false,
			searchBy: '',
			selectedData: { name: '' },
			activeDefault: [{}],
			activeData: [{}],
			isSelectCliked: false,
			index: -1,
			multiActive: { name: '' },
		}
	},
	computed: {
		...mapState(['isMobile']),
		filterData(){
			var c = this.$utils.escapeRegExp(this.searchBy)
            const pattern = new RegExp(c, 'i')
            return this.activeData.filter(c => {
                return pattern.test(c.name)
            })
        }
	},
	watch: {
		data: function(){
			this.clone()
			this.init();
		},
		default: function(){
			this.clone()
			this.init();
		}
	},
	methods: {
		init(){
			document.addEventListener('click', this.outsideClick)
			if(this.multiple){
				if(this.default.length <= 0) return
				this.named(this.default)
				this.emit()
			}else {
				//#GET FIRST DEFAUL VALUE
            	this.selectedData = (this.default.length > 0) ? this.default[0] : [{ name: ''}]
            	this.emit()
			}
		},
		emit(){
			if(this.multiple){
				this.$emit('send', this.activeDefault)
      			this.$emit('input', this.activeDefault)
			}else{
				this.$emit('send', this.selectedData)
      			this.$emit('input', this.selectedData)
			}
		},
		named(objs){
			var i = 1;
			this.selectedData.name=''
			objs.filter(d => {
            	this.selectedData.name += `${d.name}${(i < objs.length) ? ", " : ""}`
            	i++;
        	}) 
		},
		showSelect(){
			this.isShowSelect = !this.isShowSelect
		},
		selected(ob){
			this.showSelect()
			if(!this.multiple){
				this.selectedData = ob
			}else{
				if(this.matchDefault(ob.id)){
					this.activeDefault=this.activeDefault.filter(d=> { return d.id !== ob.id })
					this.named(this.activeDefault)
				}else {
					this.activeDefault.push(ob)
					this.named(this.activeDefault)
				}
			}
			this.reset()
			this.emit()
		},
		addSelect(){
			if(this.filterData.length === 1){
				this.selected(this.filterData[0])
			}else if(this.filterData.length <= 0 && this.multiple){
				var ob = { id: `new-${this.activeData.length}`, name: this.searchBy, newItem: true }
				this.activeData.unshift(ob)
				this.activeDefault.push(ob)
				this.named(this.activeDefault)
				this.reset()
				this.index = -1
				this.showSelect()
			}else if(this.searchBy===''){
				this.showSelect()
			}
		},
		selectByUpDown(e){
			if(e.keyCode===40){//down
				if(this.index < this.activeData.length-1){
					this.index++
				} else {
					this.index = this.activeData.length-1
				}
			}else if(e.keyCode===38) {//up
				if(this.index > 0){
					this.index--
				}else {
					this.index = 0;
				}
			}
			if(this.multiple){
				if(this.index === -1) return
				this.multiActive = this.activeData[this.index];
				setTimeout(()=> {
					var par = document.querySelector('.drops');
					var selectedEl = document.querySelector('.drops > a.active-multi');
					if(selectedEl){
						var top = selectedEl.offsetTop;
						var diff = top - par.clientHeight
						par.scrollTo(0, 0)
						if(diff >= 0){
							par.scrollTo(0, diff)
						}
					}
					if(e.keyCode===13){
						this.selected(this.multiActive)
						this.multiActive= { id: null, name: null}
						this.isShowSelect=false
						this.index = -1
					}
				}, 100)
			}else{
				if(this.index === -1) return
				this.selectedData = this.data[this.index];
				setTimeout(()=> {
					var par = document.querySelector('.drops');
					var selectedEl = document.querySelector('.drops > a.is-active');
					if(selectedEl){
						var top = selectedEl.offsetTop;
						var diff = top - par.clientHeight
						par.scrollTo(0, 0)
						if(diff >= 0){
							par.scrollTo(0, diff)
						}
					}
					if(e.keyCode===13){
						this.index = -1 
						this.showSelect()
					}
				}, 100)	
			}//scroll to selected el
		},
		matchDefault(id){
			return (this.activeDefault.filter(d => {
				return d.id === id
			})).length > 0
		},
		activeClass(c){
			if(!this.multiple)
			 	return c.id===this.selectedData.id ? 'is-active' : '' 
            return	this.matchDefault(c.id) ? 'is-active multi' : ''
		},
		multipleActive(c){
			if(typeof this.multiActive === 'undefined') return
			return this.multiActive.id===c.id ? 'is-active active-multi' : ''
		},
		SelectClick(e){
	    	e.stopPropagation()
		},
		outsideClick(e){
			if(this.isShowSelect){
				this.isShowSelect=false
			}
		},
		reset(){
			this.searchBy=''
		},
		clone(){
			this.activeDefault = this.$utils.cloneLoop(this.default)
			this.activeData =  this.$utils.cloneLoop(this.data)
		}
	},
	mounted(){
		this.init()
	},
	created(){
		this.clone()
	},
	beforeDestroy(){
		document.removeEventListener('click', this.outsideClick)
	}

}
</script>
<style scoped>
.select-drop-down.admin.s {
	display: block;
}
.multi{
	position: relative;
}
.un-select {
	position: absolute;
    right: 10px;
    top: 4px;
    width: 34px;
    height: 34px;
}
.drops{
    overflow-y: auto;
    overflow-x: hidden;
}
.drops.is-5{
	height: 216px;
}
.drops > a{
	background-color: transparent;
}
.select-drop-down > a.admin {
	background-color: transparent;
}
</style>