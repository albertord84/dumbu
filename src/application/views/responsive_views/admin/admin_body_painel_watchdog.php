<br><br>
<form action="<?php echo base_url().'index.php/admin/list_filter_view'?>" method="post">  
        <div id="login_container2">
            <div id="admin_form" class="row">
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    <div class="center filters">
                    <div class="center filters">
                        <b>ID do cliente</b>
                        <input id="client_id" class="form-control" placeholder="ID do cliente">
                    </div>
                        
                    </div> 
                </div>
                <div class="col-md-4">
                    <div class="center filters">
                    <!--<b>Assinatura (inic)</b>
                        <input id = "signin_initial_date" type="text" class="form-control"  placeholder="MM/DD/YYYY" >-->
                        <table>
                            <tr>
                                <th class="center filters" colspan="5">Data inicial</th>
                                <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            </tr>
                            <tr>
                                <td><select id="day1" class="form-control" style="min-width: 60px">
                                    <option value="0">Dia</option>
                                    <?php for ($day = 1; $day <= 31; $day++) { ?>
                                    <option value="<?php echo strlen($day)==1 ? '0'.$day : $day; ?>"><?php echo strlen($day)==1 ? '0'.$day : $day; ?></option>
                                    <?php } ?>
                                    </select></td>
                                    <td>&nbsp;<b>/</b>&nbsp;</td>
                                <td><select id="month1" class="form-control" style="min-width: 70px">
                                    <option value="0">Mês</option>
                                    <?php for ($month = 1; $month <= 12; $month++) { ?>
                                    <option value="<?php echo strlen($month)==1 ? '0'.$month : $month; ?>"><?php echo strlen($month)==1 ? '0'.$month : $month; ?></option>
                                    <?php } ?>
                                    </select></td>
                                <td>&nbsp;<b>/</b>&nbsp;</td>
                                <td><select id="year1" class="form-control" style="min-width: 75px">
                                    <option value="0">Ano</option>
                                    <?php for ($year = 2016; $year <= date('Y'); $year++) { ?>
                                    <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                    <?php } ?>
                                    </select></td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                </select></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="center filters">
                    <!--<b>Assinatura (inic)</b>
                        <input id = "signin_initial_date" type="text" class="form-control"  placeholder="MM/DD/YYYY" >-->
                        <table>
                            <tr>
                                <th class="center filters" colspan="5">Data final</th>
                                <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            </tr>
                            <tr>
                                <td><select id="day2" class="form-control" style="min-width: 60px">
                                    <option value="0">Dia</option>
                                    <?php for ($day = 1; $day <= 31; $day++) { ?>
                                    <option value="<?php echo strlen($day)==1 ? '0'.$day : $day; ?>"><?php echo strlen($day)==1 ? '0'.$day : $day; ?></option>
                                    <?php } ?>
                                    </select></td>
                                    <td>&nbsp;<b>/</b>&nbsp;</td>
                                <td><select id="month2" class="form-control" style="min-width: 70px">
                                    <option value="0">Mês</option>
                                    <?php for ($month = 1; $month <= 12; $month++) { ?>
                                    <option value="<?php echo strlen($month)==1 ? '0'.$month : $month; ?>"><?php echo strlen($month)==1 ? '0'.$month : $month; ?></option>
                                    <?php } ?>
                                    </select></td>
                                <td>&nbsp;<b>/</b>&nbsp;</td>
                                <td><select id="year2" class="form-control" style="min-width: 75px">
                                    <option value="0">Ano</option>
                                    <?php for ($year = 2016; $year <= date('Y'); $year++) { ?>
                                    <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                    <?php } ?>
                                    </select></td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                </select></td>
                            </tr>
                        </table>
                    </div>
                </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="center">
                        <br>
                        <button  style="min-width:200px" id = "execute_querywd" type="button" class="btn btn-success ladda-button"  data-style="expand-left" data-spinner-color="#ffffff">
                            <span class="ladda-label">Listar</span>
                        </button>
                    </div>
                </div>
            </div>        
        </div>
        <hr>
        <br><br>
        
        <div class="row">
            <div class="col-xs-1"></div>
            <div class="col-xs-10">
                <?php
                    if(isset($result)){
                        $num_rows=count($result);
                        echo '<br><p><b style="color:red">Total de registros: </b><b>'.$num_rows.'</b></p><br>';
                    }
                ?>
            </div>
            <div class="col-xs-1"></div>
        </div>

        <div class="row">
            <div class="col-xs-1"></div>
            <div class="col-xs-6">
                <table class="table">
                    <tr class="list-group-item-success">
                        <td style="max-width:240px; padding:5px"><b>Action</b></td>
                        <td style="max-width:240px; padding:5px"><b>Data</b></td>
                    </tr>
                </table>
            </div>
            <div class="col-xs-1"></div>
        </div>
    
        
        
    
        </div>
        <div class="col-xs-1"></div>    
    </div>
