<?php
##要打开shell_exec函数，默认为禁用
$json = file_get_contents("php://input");
$data = json_decode($json, true);
##加一些验证，不要裸奔
##我这里就是弄了密码，webhook弄了个密码
if ($data['password'] == '335825098') {
    if (isset($data['ref']) && $data['total_commits_count'] > 0) {
        $res = PHP_EOL . "pull start ---------------------------------------------" . PHP_EOL;
        $res .= shell_exec("cd /var/www/html/jluzh/ && git pull 2<&1");

##注释的地方我做的一些分支判断更新，根据自己的需求来，基本第一个就可以满足了。
        // if(strpos($data['ref'],'dev1')>0){
        //      $res .= shell_exec("cd 项目所在目录 &&  git stash && git pull origin dev1 && cd .git && chown -R www:www objects 2<&1");

        // }
        // elseif (strpos($data['ref'],'master')>0) {
        //      $res .= shell_exec("cd 项目所在目录 &&  git pull origin master && cd .git && chown -R www:www objects 2<&1 ");
        // }else{

        // }

        $res_log = '------------------------------------------------------------' . PHP_EOL;
        $res_log .= $data['user_name'] . ' 在' . date('Y-m-d H:i:s') . '向' . $data['repository']['name'] . '项目的' . $data['ref'] . '分支push了' . $data['total_commits_count'] . '个commit：' . $data['commits']['message'];
        $res_log .= $res . PHP_EOL;
        $res_log .= "pull end -----------------------------------------------------" . PHP_EOL;
        file_put_contents("/var/www/log/jluzh/" . date('Y-m-d', time()) . ".txt", $res_log, FILE_APPEND);//写入日志到log文件中
    }
}


////git webhook 自动部署脚本
////项目存放物理路径,第一次clone时,必须保证该目录为空
//$savePath = "/www/wwwroot/testweb/";
//$gitPath = "https://gitee.com/channel_haixia/testhooks.git";//代码仓库
//$email = "335825098@qq.com";//用户仓库邮箱
//$name = "zzh13090+";//仓库用户名,一般和邮箱一致即可
//
//$isClone = false;//设置是否已经Clone到本地,true:已经clone,直接pull,false:先clone.
//
////如果已经clone过,则直接拉去代码
//if ($isClone) {
//    $requestBody = file_get_contents("php://input");
//    if (empty($requestBody)) {
//        die('send fail');
//    }
//
//    //解析Git服务器通知过来的JSON信息
//    $content = json_decode($requestBody, true);
//    //若是主分支且提交数大于0
//    if ($content['ref'] == 'refs/heads/master' && $content['total_commits_count'] > 0) {
//
//        $res = PHP_EOL . "pull start --------" . PHP_EOL;
//        $res .= shell_exec("cd {$savePath} && git pull {$gitPath}");//拉去代码
//        $res_log = '-------------------------' . PHP_EOL;
//        $res_log .= $content['user_name'] . ' 在' . date('Y-m-d H:i:s') . '向' . $content['repository']['name'] . '项目的' . $content['ref'] . '分支push了' . $content['total_commits_count'] . '个commit：';
//        $res_log .= $res . PHP_EOL;
//        $res_log .= "pull end --------" . PHP_EOL;
//        file_put_contents("/www/wwwroot/hook.weilinit.com/git-webhook_log.txt", $res_log, FILE_APPEND);//写入日志到log文件中
//    }
//} else {
//    $res = "clone start --------" . PHP_EOL;
//    //注:在这里需要设置用户邮箱和用户名,不然后面无法拉去代码
//    $res .= shell_exec("git config --global user.email {$email}}") . PHP_EOL;
//    $res .= shell_exec("git config --global user.name {$name}}") . PHP_EOL;
//    $res .= shell_exec("git clone {$gitPath} {$savePath}") . PHP_EOL;
//    $res .= "clone end --------" . PHP_EOL;
//    file_put_contents("/www/wwwroot/hook.weilinit.com/git-webhook_log1.txt", $res, FILE_APPEND);//写入日志到log文件中
//}
