
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Floor Plan 3D Viewer</title>
    <script type="text/javascript" src = "<?php echo base_url(); ?>js/jquery.js"></script>
    <script type="text/javascript" src = "<?php echo base_url(); ?>js/bootstrap.js"></script>
    <script type='text/javascript' src='<?php echo base_url() ?>js/x3dom.js'> </script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/bootstrap.css">
    <link rel='stylesheet' type='text/css' href='<?php echo base_url() ?>css/style.css'/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/font-awesome.min.css">
    <link rel='stylesheet' type='text/css' href='<?php echo base_url() ?>css/x3dom.css'/>


</head>
<body>
    <div class="container">
        <div class="well">
            <div class="panel panel-default">
              <!-- Default panel contents -->
                <div class="panel-heading panel-leasing"><i class="fa fa-street-view"></i> Floor Plan Viewer</div>
                <div class="panel-body">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-11 col-md-offset-1">
                                <x3d showStat="false" style="background-color:#40575f;width:92%;height:80%" showLog="false" x="200" y="100"  altImg="helloX3D-alt.png">
                                    <scene>
                                         <Inline nameSpaceName="Floor" mapDEFToID="true" url="<?php echo base_url() ?>assets/3D/<?php echo $model ?>" />
                                    </scene>
                                </x3d>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- END OF WELL DIV  -->

    </div>



</body>
</html>
