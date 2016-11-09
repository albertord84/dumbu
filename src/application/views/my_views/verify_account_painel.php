

<script type="text/javascript" src="<?php echo base_url().'assets/js/verify_account.js'?>"></script>

<link type="text/css" rel="stylesheet" href="<?php echo base_url().'assets/css/insert_profile.css'?>">

<div style="z-index:2; width:100%;height:100%; background-color:white">
    <div style="z-index:3; position:absolute; top:4%; left:15%; width:70%; height:70%;">
        <center>
            <img style="z-index:4; border-radius: 50px;" width="100px" height="100px"  src="<?php echo $profile_pic_url;?>">
            <br><b style="font-family:sans-serif; font-size:18px"><?php echo $user_login; ?></b>            
            <p style="font-size:22px; width:70%; text-align:justify" >Prezado(a) <?php echo $full_name; ?>, sua conta encontra-se deshabilita temporáriamente pelo Instagram.
                Precisamos que você verifique sua conta diretamente no Instagram, ou acessando mediante o enlace: 
            </p>                
            <a style="color:blue; font-size:16px; width:70%; text-align:center" target="_blank" href="<?php echo $verify_link;?>">
                <?php echo $verify_link;?>
            </a>
            <p style="font-size:22px; width:70%; text-align:justify">    
                e depois poderá continuar seu trabalho no 
                 <a style="color:blue; font-size:22px; width:70%;" href="<?php echo base_url().'index.php/welcome/'.$return_link;?>">
                    DUMBU
                </a>.
            </p>
        </center>
    </div>
</div>
