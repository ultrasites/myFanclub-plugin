<style>
    #myfc_ownData_wrapper{
        width: 100%;
        padding: 10px;
    }

    #myfc_ownData_wrapper #myfc_ownData_addressBlock{
        border: 1px solid #000000;
        margin: 5px;
        padding: 5px;
    }

    #myfc_ownData_wrapper #myfc_ownData_profileIcon{
        font-size: 9em;
    }

    #myfc_ownData_wrapper #myfc_ownData_editBlock{
        width: 100%;
        margin: 12px;
    }

    #myfc_ownData_wrapper #myfc_ownData_editBlock input{
        width: 100%;
        padding: 5px;
        margin: 3px;
    }




</style>
<div id="myfc_ownData_wrapper" class="row">
    <h1 class="headline">Deine Mitgliedsdaten im Fanclub</h1>
    <section class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div id="myfc_ownData_addressBlock" class="row">
            <section class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <i id="myfc_ownData_profileIcon" class="fa fa-user" aria-hidden="true"></i>
            </section>
            <section class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                {forename} {lastname}<br/>
                {street} {housenumber}<br />
                {plz} {city}<br /><br />
                <i class="fa fa-birthday-cake" aria-hidden="true"></i> {birthday} ({birthdayInYears} Jahre)<br />
                <i class="fa fa-phone" aria-hidden="true"></i> {phone}<br />
                <i class="fa fa-envelope-o" aria-hidden="true"></i> {email}<br /><br />

                <b>Im Fanclub seit:</b> {start} ({startInYears} Jahr/e)
            </section>
        </div>
    </section>
    <section class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <form method="POST">
            <div id="myfc_ownData_editBlock">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <i class="fa fa-pencil" aria-hidden="true"></i> <b>Daten &auml;ndern</b>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 myfc_ownData_label">Nachname</div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12"><input type="text" id="lastname" name="lastname"
                               value="{lastname}"/></div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 myfc_ownData_label">Vorname</div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12"><input type="text" id="forename" name="forename"
                               value="{forename}"/></div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 myfc_ownData_label">Stra&szlig;e</div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12"><input type="text" id="street" name="street"
                               value="{street}"/></div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 myfc_ownData_label">Hausnummer</div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12"><input type="text" id="housenumber" name="housenumber"
                               value="{housenumber}"/></div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 myfc_ownData_label">PLZ</div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12"><input type="text" id="plz" name="plz"
                               value="{plz}"/></div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 myfc_ownData_label">Ort</div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12"><input type="text" id="city" name="city"
                               value="{city}"/></div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 myfc_ownData_label">Email</div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12"><input type="text" id="email" name="email"
                               value="{email}"/></div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 myfc_ownData_label">Telefon/Handynummer</div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12"><input type="text" id="phone" name="phone"
                               value="{phone}"/></div>
                </div>
            </div>
            <br/>
            <input type="hidden" name="type" value="myfc_edit_ownData">
            <input type="submit" id="myfc_ownData_submitBtn"
                   class="btn btn-default"
                   style="float:left;" value="Änderungen speichern"/>
        </form>
    </section>

</div>