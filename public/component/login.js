Vue.component('login', {
    template: `
    <span>
	    <a href="javascript:;" @click="showlogin()" class="login" v-if="!userinfo.nickname">登录</a>
        <div class="userinfo" v-else><span>{{userinfo.nickname}}</span><img :src="userinfo.headimgurl"></img></div>
	    <div class="modal">
	        <div class="modalinner">
	            <a class="close sprite"></a>
	            <img :src="ewlogin">
	            <p>微信扫码自动登录</p>
	        </div>
	    </div>
    </span>
        `,
    props:['showmodal'],
    watch:{
    showmodal: function (val) {
      if(val){
        this.showlogin();
      }
    }
    },
    data: function() {
        return {
            ewlogin: '',
            Socket:null,
            userinfo:{"headimgurl":null,"nickname":null}
        }
    },
    mounted:function(){
    	this.addEvents();
    },
    methods: {
    	addEvents:function(){
    		var that=this;
    		$(document).click(function(e){
			    if($(e.target).hasClass('modal') || $(e.target).hasClass('close')){
                    that.$emit('closemodal')
			        $(".modal").hide();
			        if(that.Socket){
			        	that.Socket.close();
			        }
			    }
			})
    	},
        showlogin: function() {
            var that = this;
            $(".modal").show();
            this.$myajax('get', '/get-ticket', {}, function(r) {
                that.ewlogin = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=" + r.data.ticket;
                // 定义成自己的地址
                that.Socket = new WebSocket("ws://12.23.34.56:18888");
                that.Socket.onmessage = function(evt) {
                    console.log(evt.data)
                    that.login(evt.data)
                    that.Socket.close();
                };
                that.Socket.onopen = function(e) {
                    that.Socket.send(r.data.scene_id);
                    console.log("open")
                }
                that.Socket.onclose = function(e) {
                    // Socket.send(scene);
                    console.log("close")
                }
            })
        },
        login:function(openid){
            var that=this;
            this.$myajax('get', '/login?token='+openid, {}, function(r) {
                if(r.status==0){
                   $(".modal").hide();
                   that.userinfo=r.data;
                }
                else{
                    layer.msg(r.statusinfo);
                }
            })
        }
    }
})