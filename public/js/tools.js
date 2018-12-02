
var MyPlugin = {}
MyPlugin.install = function(Vue, options) {
    Vue.prototype.$myajax = function(type, url, postdata, callback, errorcallback) {
        $.ajax({
            type: type,
            data: postdata,
            url: '' + url,
            dataType:'json',
            // contentType: "application/json",
            success: function(data) {
                if (typeof callback == "function") {
                    callback(data);
                }
            },
            error: function(res) {
                layer.msg("系统错误")
                // if (errorcallbak) {
                //     if (typeof errorcallbak == "function") {
                //         errorcallbak(res);
                //     }
                // }
            }
        })
    }
    Vue.prototype.$get = function(type, url, postdata, callback, errorcallback) {
        $.ajax({
            type: type,
            data: postdata,
            url: url,
            dataType:'json',
            // contentType : "application/json", 
            success: function(data) {
                if (typeof callback == "function") {
                    callback(data);
                }
            },
            error: function(res) {
                layer.msg("系统错误")
                // if (errorcallbak) {
                //     if (typeof errorcallbak == "function") {
                //         errorcallbak(res);
                //     }
                // }
            }
        })
    }
    Vue.prototype.$getquery = function(name) {
        // 获取参数
        var url = window.location.search;
        // 正则筛选地址栏
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        // 匹配目标参数
        var result = url.substr(1).match(reg);
        //返回参数值
        return result ? decodeURIComponent(result[2]) : null;
    }
    Vue.prototype.$popup = function(e) {
        layer.tips("<span style='color:#828282'>" + $(e.currentTarget).attr("data-content") + "</span>", "#" + e.currentTarget.id, {
            tips: [3, "#fff"]
        });
    }
    Vue.prototype.$popdown = function(name) {
        layer.closeAll('tips');
    }
    Vue.prototype.$serialize=function(json){
        var str='';
        for(key in json){
            str=str+key+"="+(json[key]||'')+"&";
        }
        return str;
    }

}
if (Vue) {
    Vue.use(MyPlugin)
}