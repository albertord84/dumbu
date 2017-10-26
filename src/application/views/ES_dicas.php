<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Example of Bootstrap 3 Grid System</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style type="text/css">
    p{
        padding: 50px;
        font-size: 32px;
        font-weight: bold;
        text-align: center;
        background: #EEE;
    }
    .row > div p {
        background: #EEE;
        padding: 10px
    }
</style>
</head>
<body>
	<!-- Open the output in a new blank tab (Click the arrow next to "Show Output" button) and resize the window to understand how the Bootstrap responsive grid system works. -->
    <div class="container">
        <div class="row">
            <p> CONSEJOS PARA MEJORAR SU RENDIMIENTO</p></div>
        </div>
        
        <div class="row">
            <div class="col-md-4"><p>Box 1</p></div>
            <div class="col-md-4"><p>Box 2</p></div>
            <div class="col-md-4"><p>Box 3</p></div>
        </div>
        
        <div class="row">
            <div class="col-md-4"><p>Box 1</p></div>
            <div class="col-md-4"><p>Box 2</p></div>
            <div class="col-md-4"><p>Box 3</p></div>
        </div>
        
        <div class="row">
            <div class="col-md-4"><p>Box 1</p></div>
            <div class="col-md-4"><p>Box 2</p></div>
            <div class="col-md-4"><p>Box 3</p></div>
        </div>
    </div>
</body>
</html>                              