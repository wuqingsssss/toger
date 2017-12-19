cordova.define('cordova/plugin_list', function(require, exports, module) {
module.exports = [
    {
        "file": "plugins/com.justep.cordova.plugin.alipay/www/alipay.js",
        "id": "com.justep.cordova.plugin.alipay.alipay",
        "pluginId": "com.justep.cordova.plugin.alipay",
        "clobbers": [
            "navigator.alipay"
        ]
    },
    {
        "file": "plugins/cordova-plugin-device/www/device.js",
        "id": "cordova-plugin-device.device",
        "pluginId": "cordova-plugin-device",
        "clobbers": [
            "device"
        ]
    },
    {
        "file": "plugins/cordova-plugin-dialogs/www/notification.js",
        "id": "cordova-plugin-dialogs.notification",
        "pluginId": "cordova-plugin-dialogs",
        "merges": [
            "navigator.notification"
        ]
    },
    {
        "file": "plugins/cordova-plugin-qqsdk/www/ycqq.js",
        "id": "cordova-plugin-qqsdk.ycqq",
        "pluginId": "cordova-plugin-qqsdk",
        "clobbers": [
            "YCQQ"
        ]
    },
    {
        "file": "plugins/cordova-plugin-splashscreen/www/splashscreen.js",
        "id": "cordova-plugin-splashscreen.SplashScreen",
        "pluginId": "cordova-plugin-splashscreen",
        "clobbers": [
            "navigator.splashscreen"
        ]
    },
    {
        "file": "plugins/cordova-plugin-wechat/www/wechat.js",
        "id": "cordova-plugin-wechat.Wechat",
        "pluginId": "cordova-plugin-wechat",
        "clobbers": [
            "Wechat"
        ]
    },
    {
        "file": "plugins/cordova-plugin-weibo/www/weibo.js",
        "id": "cordova-plugin-weibo.weibo",
        "pluginId": "cordova-plugin-weibo",
        "clobbers": [
            "window.weibo"
        ]
    }
];
module.exports.metadata = 
// TOP OF METADATA
{}
// BOTTOM OF METADATA
});