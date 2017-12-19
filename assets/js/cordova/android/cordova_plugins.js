cordova.define('cordova/plugin_list', function(require, exports, module) {
module.exports = [
    {
        "file": "plugins/cordova-plugin-whitelist/whitelist.js",
        "id": "cordova-plugin-whitelist.whitelist",
        "runs": true
    },
    {
        "file": "plugins/cordova-plugin-splashscreen/www/splashscreen.js",
        "id": "cordova-plugin-splashscreen.SplashScreen",
        "clobbers": [
            "navigator.splashscreen"
        ]
    },
    {
        "file": "plugins/cordova-plugin-device/www/device.js",
        "id": "cordova-plugin-device.device",
        "clobbers": [
            "device"
        ]
    },
    {
        "file": "plugins/cordova-plugin-dialogs/www/notification.js",
        "id": "cordova-plugin-dialogs.notification",
        "merges": [
            "navigator.notification"
        ]
    },
    {
        "file": "plugins/cordova-plugin-dialogs/www/android/notification.js",
        "id": "cordova-plugin-dialogs.notification_android",
        "merges": [
            "navigator.notification"
        ]
    },
    {
        "file": "plugins/cordova-plugin-qqsdk/www/ycqq.js",
        "id": "cordova-plugin-qqsdk.ycqq",
        "clobbers": [
            "YCQQ"
        ]
    },
    {
        "file": "plugins/cordova-plugin-wechat/www/wechat.js",
        "id": "cordova-plugin-wechat.Wechat",
        "clobbers": [
            "Wechat"
        ]
    },
    {
        "file": "plugins/cordova-plugin-weibo/www/weibo.js",
        "id": "cordova-plugin-weibo.weibo",
        "clobbers": [
            "window.weibo"
        ]
    },
    {
        "file": "plugins/com.justep.cordova.plugin.alipay/www/alipay.js",
        "id": "com.justep.cordova.plugin.alipay.alipay",
        "clobbers": [
            "navigator.alipay"
        ]
    }
];
module.exports.metadata = 
// TOP OF METADATA
{
    "cordova-plugin-whitelist": "1.2.1",
    "cordova-plugin-splashscreen": "3.1.0",
    "cordova-plugin-device": "1.1.1",
    "cordova-plugin-dialogs": "1.2.0",
    "cordova-plugin-qqsdk": "0.3.9",
    "cordova-plugin-wechat": "1.1.3",
    "cordova-plugin-weibo": "1.3.0",
    "com.justep.cordova.plugin.alipay": "5.3.0"
};
// BOTTOM OF METADATA
});