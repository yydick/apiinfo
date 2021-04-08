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
            <a class="navbar-brand" title="logoTitle" href="#">{{$test}}</a>
       </div>
       <div class="collapse navbar-collapse">
           <ul class="nav navbar-nav navbar-right">
               <li role="presentation">
                   <a href="#">当前用户：<span class="badge">{{$controllerPath}}</span></a>
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
         <ul class="nav nav-stacked nav-pills">
             <li role="presentation" class="active">
                 <a href="/apiinfo">欢迎页</a>
             </li>
             <li role="presentation">
                 <a href="/contents?group=123&name=abc" target="mainFrame">导航链接1</a>
             </li>
             <li role="presentation">
                 <a href="/apiinfo/test" target="mainFrame">导航链接2</a>
             </li>
             <li role="presentation">
                 <a href="nav3.html" target="mainFrame">导航链接3</a>
             </li>
             <li class="dropdown">
                 <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                     导航链接4<span class="caret"></span>
                 </a>
                 <ul class="dropdown-menu">
                     <li>
                         <a href="nav1.html" target="mainFrame">导航链接4-1</a>
                     </li>
                     <li>
                         <a href="nav2.html" target="mainFrame">导航链接4-2</a>
                     </li>
                     <li>
                         <a href="nav3.html" target="mainFrame">导航链接4-3</a>
                     </li>
                 </ul>
             </li>
             <li role="presentation">
                 <a href="nav4.html" target="mainFrame">导航链接5</a>
             </li>
         </ul>
     </div>
     <!-- 左侧导航和正文内容的分隔线 -->
     <div class="splitter"></div>
     <!-- 正文内容部分 -->
     <div class="pageContent">
         <iframe src="/contents" id="mainFrame" name="mainFrame" frameborder="0" width="100%"  height="100%" frameBorder="0"></iframe>
     </div>
 </div>
 <!-- 底部页脚部分 -->
 <div class="footer">
     <p class="text-center">
         2017 &copy; NeoYang.
     </p>
 </div>
 <script type="text/javascript">
 $(".nav li").click(function() {
    $(".active").removeClass('active');
    $(this).addClass("active");
});
 </script>
    </body>
</html>