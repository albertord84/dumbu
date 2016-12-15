
<br><br>

    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-2">
            <div class="center filters">
                <b>Status</b>   
                <select id="client_status" class="form-control">    
                    <option value="0">--TODOS--</option>
                    <option value="1">ACTIVE</option>
                    <option value="2">BLOCKED_BY_PAYMENT</option>
                    <option value="3">BLOCKED_BY_INSTA</option>
                    <option value="4">DELETED</option>
                    <option value="7">UNFOLLOW</option>
                    <option value="8">BEGINNER</option>
                    <option value="9">VERIFY_ACCOUNT</option>
                    <option value="10">BLOCKED_BY_TIME</option>
                </select>      
            </div> 
        </div>
        <div class="col-md-2"> 
            <div class="center filters">
                <b>Data inicial</b>   
                <input id = "signin_clientLogin" type="text" class="form-control"  placeholder="DD/MM/YYYY" >       
            </div>
        </div>

        <div class="col-md-2">
            <div class="center filters">
                <b>Data final</b>   
                <input id = "signin_clientLogin" type="text" class="form-control"  placeholder="DD/MM/YYYY" >       
            </div>
        </div>
        <div class="col-md-2">
            <div class="center filters">
                 <b>Ano experação CC</b>
                <select id="client_credit_card_validate_month" class="form-control">
                    <option>--TODOS--</option>
                    <option>2017</option><option>2018</option>
                    <option>2019</option><option>2020</option><option>2021</option>
                    <option>2022</option><option>2023</option><option>2024</option>
                    <option>2025</option><option>2026</option><option>2027</option>
                    <option>2028</option><option>2029</option><option>2030</option>
                    <option>2031</option><option>2032</option><option>2033</option>
                    <option>2034</option><option>2035</option><option>2036</option>
                    <option>2037</option><option>2038</option><option>2039</option>
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="center filters">
                <b>Dia de pagamento</b>
                <select id="client_credit_card_validate_month" class="form-control">
                    <option value="0">--TODOS--</option>
                    <option>01</option><option>02</option><option>03</option><option>04</option><option>05</option>
                    <option>06</option><option>07</option><option>08</option><option>09</option><option>10</option>
                    <option>11</option><option>12</option><option>13</option><option>14</option><option>15</option>
                    <option>16</option><option>17</option><option>18</option><option>19</option><option>20</option>
                    <option>21</option><option>22</option><option>23</option><option>24</option><option>25</option>
                    <option>26</option><option>27</option><option>28</option><option>29</option><option>30</option>
                    <option>31</option>
                </select>
            </div>
        </div>    
        <div class="col-md-1"></div>
    </div>

    <br><br>
    
    <div class="row">        
            <div class="center">
                <button  style="min-width:200px" id = "execute_query" type="button" class="btn btn-success ladda-button"  data-style="expand-left" data-spinner-color="#ffffff">
                    <span class="ladda-label">Listar</span>
                </button>
           </div>
    </div>

    <br><br>
    
    <div class="row">
        <div class="col-xs-1"></div>
        <div class="col-xs-10">
            <table class="table">
                <tr class="list-group-item-success">
                    <td class="center"><b>No.</b></td>
                    <td class="center"><b>Dados pessoais</b></td>
                    <td class="center"><b>Dados de Instagaram</b></td>
                    <td class="center"><b>Dados bancários</b></td>
                    <td class="center"><b>Perfis ativos</b></td>
                    <td class="center"><b>Perfis eliminados</b></td>
                </tr>
            </table>
        </div>
        <div class="col-xs-1"></div>
    </div>
    <div class="row">
        <table>
            <?php
    //            $num_rows=count($result);
    //            for($i=0;$i<$num_rows;$i++){
    //                
    //            }
            ?>
        </table>
    </div>