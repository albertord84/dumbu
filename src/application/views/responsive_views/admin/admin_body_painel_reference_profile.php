    <br><br>
        
    <div class="row">
        <div class="col-xs-3"></div>
        <div class="col-xs-6">
            <div class="center">
                <h3>Histórico dos Perfis de Referência</h3>
                <br>
                <div style="text-align:left">
                    <table class="table">
                        <?php 
                            $n=count($my_daily_work);
                            for($i=0;$i<$n;$i++){
                                echo '<tr>';
                                    echo '<td>'.($i+1).'</td>';
                                    echo '<td>'.$my_daily_work[$i]['id'].'</td>';
                                    echo '<td>'.$my_daily_work[$i]['profile'].'</td>';
                                    echo '<td>'.$my_daily_work[$i]['to_follow'].'</td>';
                                    echo '<td>'.$my_daily_work[$i]['to_unfollow'].'</td>';
                                    if($my_daily_work[$i]['end_date'])
                                        echo '<td>'.date('d-m-Y',$my_daily_work[$i]['end_date']).'</td>';
                                    else
                                        echo '<td>---</td>';
                                    echo '<td style="color:green">ACTIVE</td>';
                                echo '</tr>';
                            }                            
                            $n=count($canceled_profiles);
                            for($i=0;$i<$n;$i++){
                                echo '<tr>';
                                    echo '<td>'.($i+1).'</td>';
                                    echo '<td>'.$canceled_profiles[$i]['id'].'</td>';
                                    echo '<td>'.$canceled_profiles[$i]['insta_name'].'</td>';
                                    echo '<td>---</td>';
                                    echo '<td>---</td>';   
                                    if($canceled_profiles[$i]['end_date'])
                                        echo '<td>'.date('d-m-Y',$canceled_profiles[$i]['end_date']).'</td>';
                                    else
                                        echo '<td>---</td>';
                                    echo '<td style="color:red">UNACTIVE</td>';
                                echo '</tr>';
                            }                            
                        ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xs-3"></div>            
    </div>