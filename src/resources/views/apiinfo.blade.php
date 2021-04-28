<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="{{URL::asset('/vendor/apiinfo/js/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
        <link href="{{URL::asset('/vendor/apiinfo/css/apiinfo.css')}}" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" type="text/javascript"></script>
        <script src="{{URL::asset('/vendor/apiinfo/js/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
</head>
    <body class="antialiased">
    <!--顶部导航栏部分-->
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" title="logoTitle" href="#">ApiInfo</a>
       </div>
       <div class="collapse navbar-collapse">
           <ul class="nav navbar-nav navbar-right">
                <li role="presentation">
                    <input type="text" id="baseUrl" value="{{$url}}" size=80 style="margin-top: 10px;" />
                </li>
               <li role="presentation">
                   <a href="#">当前用户：<span class="badge">apiinfo</span></a>
               </li>
               <li>
                   <a href="../login/logout">
                         <span class="glyphicon glyphicon-lock"></span>退出登录</a>
                </li>
            </ul>
       </div>
    </div>
</nav>
<!-- 中间主体内容部分 -->
<div class="pageContainer">
     <!-- 左侧导航栏 -->
     <div class="pageSidebar">
     <div class="form-group">
        <input type="text" class="form-control" id="search" value="请输入..." style="margin: 10px 50px 10px 10px; overflow: hidden; width: 90%;" />
     </div>
         <ul class="nav nav-stacked nav-pills">
             <li role="presentation" class="active">
                 <a href="/apiinfo">欢迎页</a>
             </li>
         @foreach ($trees as $groupName => $items)
             <li role="presentation">
                 <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                     {{$groupName}}<span class="caret"></span>
                 </a>
                 <ul class="dropdown-menu">
                 @foreach ($items as $apiName => $item)
                     <li>
                         <a href="/apiinfo/contents?group={{$item['classGroup']}}&name={{$item['methodName']}}" target="mainFrame">{{$item['methodDoc']}}</a>
                     </li>
                 @endforeach
                 </ul>
             </li>
         @endforeach
         </ul>
     </div>
     <!-- 左侧导航和正文内容的分隔线 -->
     <div class="splitter"></div>
     <!-- 正文内容部分 -->
     <div class="pageContent">
         <iframe src="/apiinfo/welcome" id="mainFrame" name="mainFrame" frameborder="0" width="100%"  height="100%" frameBorder="0"></iframe>
     </div>
 </div>

 <!-- 底部页脚部分 -->
 <div class="footer">
     <p class="text-center">
         2021 - {{date('Y')}} &copy; ApiInfo.
     </p>
 </div>
 <script type="text/javascript">
    $(".nav li").click(function() {
        $(".active").removeClass('active');
        $(this).addClass("active");
    });
	/* 搜索框  */

  window.onload=function () {
   function $id(id) {
    return document.getElementById(id);
   } //虽然该案例中这种获取id方式比较麻烦但这是通常的一种方法避免一直去写获取
   $id("search").onfocus=function () {
    if (this.value=="请输入..."){
     this.value=""; //这边是赋值别搞错了
     this.style.color="#333"; //黑色
    }
   } //当用户点击时初始文字消失
   $id("search").onblur=function () {
    if (this.value==""){
     this.value="请输入..."
     this.style.color="#ccc"; //透明灰
    } //谁调用this指向谁
   }//当为空时用户点击其他地方显示初始化状态
  }
 </script>
    </body>
</html>
