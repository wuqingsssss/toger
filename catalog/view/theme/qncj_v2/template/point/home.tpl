<style type="text/css">
    #ztd {
        /*padding: 3px;
        background: #FFCC7D;*/
        width: 600px;
    }

    #ztd .ztd-content {
        display: block;
        padding: 10px;
        padding-bottom:0;
        background: rgb(255, 255, 255);
        overflow: hidden;
    }

    #ztd .ztd-content-right {
    }

    #ztd .ztd-content .tip {
        font-size: 18px;
    }

    #ztd input {
    }

    #ztd a.active{
        color: red;
    }

    #location-form {
        overflow: hidden;
    }

    #location-form .td1 input {
        font-size: 16px;
        width: 100px;
    }

    #location-form .td {
        float: left;
        display: inline-block;
    }

    #location-form .td.td1 {
        width: 150px;
    }

    #location-form .td.td2 {
        width: 80px;
    }
    #ztd dl{ padding-left:80px; float：left; overflow:hidden; clear:left;  position:relative; margin-bottom:20px; }
    #ztd dt{ width:80px; position:absolute; top:0; left:0; }

    #ztd dd{ display:inline-block; margin-right:20px; }

    .ztd-point{  }
    .i-point-detail{ width:600px; margin-left:-10px; margin-right:-10px; background-color: #F2ECEC; height:auto;  }
    .point-detail{ padding:10px; }

</style>
<div id="ztd-hidden-html" style="display: none;">
    <div id="ztd" data-url="index.php?route=point/home/initdata" >
        <div class="ztd-content">
            <div class="ztd-content-right">
                <p class="tip" style="margin-top:10px; margin-bottom:20px;">请选择您附近的自提点区域</p>

                <div class="step1" style="margin-top:10px;">
                    <div class="i-area ztd-area">
                        <dl>
                            <dt>所在区域:</dt>
                        </dl>
                    </div>
                    <div class="i-cbd ztd-cbd">
                        <dl>
                            <dt>所在商圈:</dt>
                        </dl>
                    </div>
                    <div class="i-point ztd-point">
                        <dl>
                            <dt>取菜点:</dt>
                        </dl>
                    </div>
                    <div class="i-point-detail" style="display:none;">
                        <div class="point-detail"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/module/global_ztd.js?v=1.3"></script>

  
  