#!/usr/bin/python
# -*- coding: utf-8 -*-

#  版本:    v0.3
#
#
#  更新履历:
#       2016-01-20    v0.1    新规作成    张萌
#       2016-01-28    v0.2    版本更新    张萌
#           1.  注释[ print global_value.CMD_STRING ]
#           2.  删除[ test ]方法
#               def test():
#                   now_time = time.time()
#                   print '%s\tTime\n' % now_time
#           3.  增加[ 开始行号 ]和[ 结束行号 ]参数
#           4.  增加[ 开始行号 ]和[ 结束行号 ]参数判断
#           5.  增加[ 开始行号 ]和[ 结束行号 ]分页显示查询结果
#           6.  增加对[ UnicodeEncodeError ]字符集显示错误的对应
#           7.  增加对[ IOError ]输出错误的对应
#       2016-01-28    v0.3    版本更新    张萌
#           1.  增加[ 开始行号 ]和[ 结束行号 ]为0时输出全部查询结果
#           2.  更改输出结果行数限制
#           3.  更改[ 开始行号 ]参数输入时可以为[ 0 ]
#           4.  删除部分无用和注释掉的代码
#           5.  防止程序在运行完成后不能推出,程序最后增加[ sys.exit(0) ]
#
#
#  功能:
#   * 各参数间以半角空格(" ")间隔
#       参数:
#           1.  日期区间(开始日期)    必须
#               例:  20160101
#           2.  日期区间(结束日期)    必须
#               例:  20160120
#           3.  关键字              必须
#               例:  localhost
#           4.  开始行号            必须
#               * 必须为数字,参数可以为0
#               例:  10
#           5.  结束行号            必须
#               * 必须为数字
#               * 参数均为0时则输出全部查询结果
#               例:  5
#           6.  服务器名            必须
#               例:  WEB(需要大写)
#           7.  日志类型            必须
#               例:  www,nginx,php,api,unison,syslogng
#           8.  查询文件名           必须
#               * 此参数可为多个,不限制文件个数,但至少需要一个文件
#               例:  admin_log.log
#
#
#  调用方法:
#   PHP:
#       1.  全量输出在页面
#               passthru('python full路径 参数');
#       2.  选择输出在页面
#               接回传值变量 = array();
#               exec('python full路径 参数', 接回传值变量);
#               echo 接回传值变量[0];
#               print_r(接回传值变量);
#       3.  全量输出在页面
#               system('python full路径 参数');

import datetime
import time
import sys
import paramiko
import global_value
# import json
# import threading


#  参数check
def param():
    if len(sys.argv) >= 6:
        #  开始日期
        try:
            time.strptime(sys.argv[1], "%Y%m%d")
            global_value.START_DAY = sys.argv[1]
        except:
            return '开始日期: %s,日期格式不对,请重新输入该参数.' % (sys.argv[1])

        # 结束日期
        try:
            time.strptime(sys.argv[2], "%Y%m%d")
            global_value.END_DAY = sys.argv[2]
        except:
            return '结束日期: %s,日期格式不对,请重新输入该参数.' % (sys.argv[2])

        # 关键字
        if sys.argv[3] == "" or sys.argv[3] is None or sys.argv[3] == '':
            return '关键字: %s,不能为空,请重新输入该参数.' % (sys.argv[3])
        else:
            global_value.KEY_STRING = sys.argv[3]

        # 开始行号
        try:
            int(sys.argv[4])
            global_value.START_ROW = sys.argv[4]
        except:
            return '开始行号: %s,开始行号格式不对,请重新输入该参数.' % (sys.argv[4])

        # 结束行号
        try:
            int(sys.argv[5])
            global_value.END_ROW = sys.argv[5]
        except:
            return '结束行号: %s,结束行号格式不对,请重新输入该参数.' % (sys.argv[5])

        # 服务器名
        if sys.argv[6] == "" or sys.argv[6] is None or sys.argv[6] == '':
            return '服务器名: %s,不能为空,请重新输入该参数.' % (sys.argv[6])
        else:
            global_value.SERVER_NAME = sys.argv[6]

        # 日志类型
        if sys.argv[7] == "" or sys.argv[7] is None or sys.argv[7] == '':
            return '日志类型: %s,不能为空,请重新输入该参数.' % (sys.argv[7])
        else:
            global_value.LOG_TYPE = sys.argv[7]

        # 文件名
        for param_cnt in range(8, len(sys.argv)):
            if ".log" not in sys.argv[param_cnt]:
                return '查询文件名: %s,不是[.log]结尾的日志文件,请重新输入该参数.' % (sys.argv[param_cnt])

    else:
        return '参数个数: %d,没有可用参数,请重新输入查询参数.' % (len(sys.argv))

    return 0


#  命令拼接测试
def cmd_str_test():
    global_value.CMD_STRING = ['grep "localhost" /var/log/syslog-ng/net.log /var/log/syslog-ng/err.log | grep "Jan 21"']


#  命令拼接
def cmd_str():
    global_value.CMD_STRING = 'grep "' + sys.argv[3] + '" '
    for file_cnt in range(6, len(sys.argv)):
        for log_cnt in range((datetime.datetime.strptime(global_value.END_DAY, "%Y%m%d") -
                                      datetime.datetime.strptime(global_value.START_DAY, "%Y%m%d")).days + 1):
            log_day = datetime.datetime.strftime(datetime.datetime.strptime(global_value.START_DAY, "%Y%m%d") +
                                                 datetime.timedelta(log_cnt), "%Y%m%d")
            if log_day == datetime.datetime.strftime(datetime.datetime.now(), "%Y%m%d"):
                global_value.CMD_STRING += '/db_data/syslog-ng/' + global_value.SERVER_NAME +\
                                      '*/' + global_value.SERVER_NAME + '*_' + global_value.LOG_TYPE +\
                                      '_*' + sys.argv[file_cnt] + " "
            else:
                global_value.CMD_STRING += '/db_data/syslog-ng/' + global_value.SERVER_NAME +\
                                      '*/' + log_day + '_' + global_value.SERVER_NAME + '*_' + global_value.LOG_TYPE +\
                                      '_*' + sys.argv[file_cnt] + " "
    if global_value.END_ROW == 0:
        global_value.CMD_STRING += '| awk \'NR>=' + global_value.START_ROW + ' {print $0}\''
    else:
        # global_value.CMD_STRING += ' | tail -n +' + global_value.START_ROW + ' | head -n ' + global_value.END_ROW
        global_value.CMD_STRING += '| awk \'NR>=' + global_value.START_ROW + ' && NR<=' +\
                                   str(int(global_value.END_ROW) + int(global_value.START_ROW)) +\
                                   ' {print $0}\''


# ssh远程连接并执行
def server_connect_ssh2(server_cmd):
    try:
        ssh = paramiko.SSHClient()
        ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
        ssh.connect(global_value.IP_ADDRESS, global_value.PORT_NUMBER, global_value.USER_NAME, global_value.PASS_WORD,
                    timeout=global_value.TIME_OUT)

        stdin, stdout, stderr = ssh.exec_command(server_cmd)
        out_str = stdout.readlines()

        ssh.close()
        return out_str
    except:
        return "server connect error"


# main处理
if __name__ == '__main__':
    #  UnicodeEncodeError: 'ascii' codec can't encode character 对应
    reload(sys)
    sys.setdefaultencoding('utf8')

    #  IOError: [Errno 32] Broken pipe 对应
    from signal import signal, SIGPIPE, SIG_DFL
    signal(SIGPIPE, SIG_DFL)

    #  参数check
    return_code = param()
    if return_code != 0:
        print return_code
        sys.exit(255)

    #  命令拼接
    cmd_str()
    #  ssh远程连接并执行
    return_arr = server_connect_ssh2(global_value.CMD_STRING)

    if isinstance(return_arr, list):
        for print_string in return_arr:
            print print_string,
    else:
        print "no data"
        print return_arr

    sys.exit(0)

    #  多线程执行
    # threads = []
    # print "Begin......"
    # for ip_cnt in range(1,254):
    #     ip = "192.168.1." + str(ip_cnt)
    #     out_str = threading.Thread(target = server_connect_ssh2, args = (global_value.CMD_STRING))
    #     out_str.start()
