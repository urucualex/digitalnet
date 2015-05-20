        <div class="clearfix clear-fix"></div>
        <div class="container">
            <div class="row">
                <div class="col-sm-10 col-sm-offset-2">
                    <hr>
                    <footer>
                        <p>&copy; <a href="http://www.info-sanatate.ro" targer="_blank">Info-Sanatate</a> 2015</p>
                    </footer>
                </div>
            </div>
        </div> <!-- /container -->  

        <div class="overlay" id="overlay">
        </div>

        <div id="select-players-template" class="template">
            <div class="panel panel-primary">
                <div class="panel-heading">Selecteaza statiile</div>
                <div class="panel-body" style="overflow: scroll">
                    <table class="table table-striped">
                        <thead>
                        </thead>
                        <tbody>
                        </tbody>                    
                    </table>
                    <button class="btn btn-primary action" data-action="select-players-ok">Ok</button>
                    <button class="btn btn-primary action" data-action="select-players-cancel">Cancel</button>
                </div>
            </div>
        </div>

        <div class="row" id="message-box">
            <div class="col-md-offset-4 col-md-4">
                <div class="panel panel-primary" > 
                    <div class="panel-heading">Title</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12 message-content" style="text-align: center">
                                Some message that must be shown
                            </div>
                        </div>
                        <p> </p>
                        <div class="row">
                            <div class="col-md-offset-4 col-md-4">
                                <button type="button" class="btn btn-primary action" data-action="message-box-hide">Ok</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script-->
        <script>window.jQuery || document.write('<script src="/js/vendor/jquery-1.11.2.min.js"><\/script>')</script>

        <script src="/js/vendor/bootstrap.min.js"></script>
        <script src="/js/lodash.js"></script>
        <script src="/js/plugins.js"></script>
        <script src="/js/helpers.js"></script>        
        <script src="/js/dinamictable.js"></script>
        <script src="/js/media_table.js"></script>
        <script src="/js/players_table.js"></script>
        <script src="/js/select_players.js"></script>        
        <script src="/js/playlist_table.js"></script>        
        <script src="/js/media_players_table.js"></script>        
        <script src="/js/main.js"></script>


        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='//www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','UA-XXXXX-X','auto');ga('send','pageview');
        </script>
    </body>
</html>
