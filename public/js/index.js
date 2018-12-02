
var app = new Vue({
    el: '#app',
    data: {
        postdata: {
            start_time: '',
            end_time: '',
            include_keywords: '',
            type: 0,
            searchtype: 0,
            page: 1,
            size: 10,
            searchscope: "title",
            region: '',
            issearch: 1
        }
    },
    created: function() {
        var that = this;

    },
    mounted: function() {
        var that = this;
        this.slide();
        this.addEvent();
    },
    watch: {

    },
    methods: {
        addEvent:function(){
            var that=this;
            $("#search").keydown(function(e){
              if(e.keyCode==13){
                that.getdata();
              }
            });
        },
    }
})