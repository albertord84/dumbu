    <br><br>
<div id="admin_form2">    
    <div class="row">
        <form id="form_listar_criar" name="form_listar_criar">
            <div class="col-xs-1"></div>
            <div class="col-xs-2">
                <div class="left">
                    <div class="row">
                        <input type="radio" id="pendence_option_listar" name="pendence_option" value="1" checked="checked" 
                               onchange="document.form_listar_criar.pendences_date.disabled=false;
                                         document.form_listar_criar.execute_query2.disabled=false;
                                         document.form_listar_criar.client_id.disabled=true;
                                         document.form_listar_criar.event_date.disabled=true;
                                         document.form_listar_criar.pendence_text.disabled=true;
                                         document.form_listar_criar.frequency_option1.disabled=true;
                                         document.form_listar_criar.frequency_option2.disabled=true;
                                         document.form_listar_criar.frequency_option3.disabled=true;
                                         document.form_listar_criar.execute_query3.disabled=true;"/><b> LISTAR PENDÊNCIAS</b>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-6">
                            <select id="pendences_date" name="pendences_date" class="form-control" style="width: 100px">
                                <option value="-50" <?php if (isset($form_filter) && $form_filter[pendences_date] == "-50") { echo 'selected'; } ?> >Anteriores</option>
                                <option value="-30" <?php if (isset($form_filter) && $form_filter[pendences_date] == "-30") { echo 'selected'; } ?> >-30 dias</option>
                                <option value="-7" <?php if (isset($form_filter) && $form_filter[pendences_date] == "-7") { echo 'selected'; } ?> >-7 dias</option>
                                <option value="-1" <?php if (isset($form_filter) && $form_filter[pendences_date] == "-1") { echo 'selected'; } ?> >Ontem</option>
                                <option value="0" <?php if (isset($form_filter)) { if ($form_filter[pendences_date] == "0") { echo 'selected'; } } else { echo 'selected'; } ?> >Hoje</option>
                                <option value="1" <?php if (isset($form_filter) && $form_filter[pendences_date] == "1") { echo 'selected'; } ?> >Amanhã</option>
                                <option value="7" <?php if (isset($form_filter) && $form_filter[pendences_date] == "7") { echo 'selected'; } ?> >+7 dias</option>
                                <option value="30" <?php if (isset($form_filter) && $form_filter[pendences_date] == "30") { echo 'selected'; } ?> >+30 dias</option>
                                <option value="50" <?php if (isset($form_filter) && $form_filter[pendences_date] == "50") { echo 'selected'; } ?> >Posteriores</option>
                            </select>
                        </div>
                        <div class="col-xs-6">
                            <button  style="min-width:100px" id="execute_query2" name="execute_query2" type="button" class="btn btn-success ladda-button"  data-style="expand-left" data-spinner-color="#ffffff">
                                <span class="ladda-label">Listar</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-1"></div>
            <div class="col-xs-4">
                <div class="row">
                    <input type="radio" id="pendence_option_criar" name="pendence_option" value="2"
                           onchange="document.form_listar_criar.pendences_date.disabled=true;
                                     document.form_listar_criar.execute_query2.disabled=true;
                                     document.form_listar_criar.client_id.disabled=false;
                                     document.form_listar_criar.event_date.disabled=false;
                                     document.form_listar_criar.pendence_text.disabled=false;
                                     document.form_listar_criar.frequency_option1.disabled=false;
                                     document.form_listar_criar.frequency_option2.disabled=false;
                                     document.form_listar_criar.frequency_option3.disabled=false;
                                     document.form_listar_criar.execute_query3.disabled=false;"/><b> CRIAR PENDÊNCIA</b>
                </div>
                <br>
                <div class="row">
                    <div class="col-xs-6">
                        <div class="center filters">
                            <b>ID do cliente</b>
                            <input id="client_id" name="client_id" class="form-control" placeholder="ID do cliente" disabled>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="center filters">
                            <b>Data da pendência</b>
                            <input id="event_date" name="event_date" type="text" class="form-control"  placeholder="MM/DD/YYYY" disabled>      
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="center filters">
                            <b>Texto da pendência</b>
                            <textarea id="pendence_text" name="pendence_text" class="form-control" placeholder="Texto da pendência" style="min-width: 380px; max-width: 380px; min-height: 75px" disabled></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-3">
                <br><br>
                <div class="row">
                    <label>Frequencia:</label>
                </div>
                <div class="row">
                    <input type="radio" id="frequency_option1" name="frequency_option" value="1" checked="checked" onchange="document.form_listar_criar.number_times.disabled=true;" disabled/> Única
                </div>
                <div class="row">
                    <input type="radio" id="frequency_option2" name="frequency_option" value="2" onchange="document.form_listar_criar.number_times.disabled=false;" disabled/>
                    <input type="text" id="number_times" name="number_times" placeholder="12" size="2" maxlength="2" disabled> vezes
                </div>
                <div class="row">
                    <input type="radio" id="frequency_option3" name="frequency_option" value="3" onchange="document.form_listar_criar.number_times.disabled=true;" disabled/> Infinita
                </div> 
                <br>
                <div class="row">
                    <div class="left">
                        <button  style="min-width:100px" id="execute_query3" name="execute_query3" type="button" class="btn btn-success ladda-button"  data-style="expand-left" data-spinner-color="#ffffff" disabled>
                            <span class="ladda-label">Criar</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-xs-1"></div>   
        </form>
    </div>
</div>
<div class="row">
    <div class="col-xs-1"></div>
    <div class="col-xs-10">
        <?php
            if (isset($result)) {
                $num_rows = count($result);
                echo '<br><p><b style="color:red">Total de registros: </b><b>'.$num_rows.'</b></p><br>';
            }
        ?>
    </div>
    <div class="col-xs-1"></div>
</div>