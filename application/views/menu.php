<?php
    if (empty($_current_controller)) {
        $_current_controller = '';
    }
?>
<header class="navbar navbar-static-top bs-docs-nav navbar-inverse navbar-fixed-top" id="top" role="banner">
    <div class="container-fluid">
        <div class="navbar-header">
            <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="/" class="navbar-brand">DigitalNet</a>
        </div>
        <nav class="collapse navbar-collapse bs-navbar-collapse">
            <ul class="nav navbar-nav">
                <li <?=($_current_controller == 'player') ? 'class="active"' : ''?> >
                    <a href="/players">Statii</a>
                </li>
                <li <?=($_current_controller == 'media') ? 'class="active"' : ''?> >
                    <a href="/media">Campanii</a>
                </li>
                <li <?=($_current_controller == 'log') ? 'class="active"' : ''?> >
                    <a href="#/log">Log</a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li <?=($_current_controller == 'user') ? 'class="active"' : ''?> ><a href="/users"><span class="glyphicon glyphicon-user"></span> Mihai Bors</a></li>
            </ul>
        </nav>
    </div>
</header>     
