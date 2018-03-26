<br><br>

    <section id="Peixe_urbano" class="col-md-12 col-sm-12 col-xs-12">
        <div  class="col-md-2 col-sm-2 col-xs-12"></div>
        <div class="col-md-8 col-sm-8 col-xs-12">
                <h1>Em desenvolvimento ainda, não usar!!</h1>
                <h3>O cliente é Peixe Urbano? Faça o seguinte:</h3>
                <h5> 1) Estorne o primeiro pagamento co cliente, caso já ter sido cobrado</h5>
                <h5> 2) Mude a data de pagamento para o dia do seguinte pagamento:</h5>
                <div class="col-md-3 col-sm-3 col-xs-12"> 
                    <b>ID do cliente</b>
                    <input id="pu_user_id" type="text" placeholder="ID do cliente">
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12"> 
                    <b>Nova data pagamento</b>
                    <div >
                        <input id="date_from" type="text"  name="date_from" placeholder="mm/dd/yyyy" class="form-control" value="<?php if (isset($form_filter) && $form_filter[date_from] != "") { echo $form_filter[date_from]; } ?>">
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12"> 
                    <input id="pu_button" class="btn btn-primary" type="button" value="Modificiar" style="margin-top:19px">                    
                </div>
        </div>
        <div class="col-md-2 col-sm-2 col-xs-12"></div>  
    </section>

    <section id="Peixe_urbano" class="col-md-12 col-sm-12 col-xs-12"><hr><br><br></section>
    
    <section id="Cobrança na hora" class="col-md-12 col-sm-12 col-xs-12">
        <div  class="col-md-2 col-sm-2 col-xs-12"></div>
        <div class="col-md-8 col-sm-8 col-xs-12">
                <h3>Uma cobrança na hora, agora mesmo?:</h3>
                <h5> ATENÇÂO: Verifique se precisa cancelar alguma recorrência ativa do cliente.</h5>
                <div class="col-md-3 col-sm-3 col-xs-12"> 
                    <b>ID do cliente</b>
                    <input id="pu_user_id" type="text" placeholder="ID do cliente">
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12"> 
                    <b>Valor (en centavos)</b>
                    <input id="pu_user_id" type="text" placeholder="Ex: 23450">
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12"> 
                    <input id="pu_button" class="btn btn-primary" type="button" value="Cobrar agora" style="margin-top:19px">
                </div>
        </div>
        <div class="col-md-2 col-sm-2 col-xs-12"></div> 
    </section>
    
    <section id="Peixe_urbano" class="col-md-12 col-sm-12 col-xs-12"><hr><br><br></section>         
    
    <section id="Peixe_urbano" class="col-md-12 col-sm-12 col-xs-12">
        <div  class="col-md-2 col-sm-2 col-xs-12"></div>
        <div class="col-md-8 col-sm-8 col-xs-12">
                <h3>Criar uma nova recorrência? Preencha os campos seguintes:</h3>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <b>ID do cliente</b>
                    <input id="pu_user_id" type="text" placeholder="ID do cliente">
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12"> 
                    <b>Valor (en centavos)</b>
                    <input id="pu_user_id" type="text" placeholder="Ex: 23450">
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <b>Nova data pagamento</b>
                        <input id="date_from_2" type="text"  name="date_from" placeholder="mm/dd/yyyy" class="form-control" value="<?php if (isset($form_filter) && $form_filter[date_from] != "") { echo $form_filter[date_from]; } ?>">
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12"> 
                    <input id="pu_button" class="btn btn-primary" type="button" value="Modificiar" style="margin-top:19px">                    
                </div>
        </div>
        <div class="col-md-2 col-sm-2 col-xs-12"></div>  
    </section>