<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<?

session_start();
if (isset($_SESSION['USER_AUTH_LOGIN'])) {
    header('Location: Panel');
}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>webhomebudget: login</title>
    <link rel="stylesheet" type="text/css" href="Libs/ExtJS4.1.1a/resources/css/ext-all-gray.css">
    <script type="text/javascript" src="Libs/ExtJS4.1.1a/bootstrap.js"></script>
    <script type="text/javascript" src="Libs/ExtJS4.1.1a/locale/ext-lang-pl.js"></script>
</head>

<body id="login_body">
    <script src="Panel/config.js"></script> 
    <script type="text/javascript">



        Ext.onReady(function() {
            Ext.create('Ext.Panel', {
                layout: 'fit',
                title: 'Logowanie',
                renderTo: Ext.getBody(),
                items: [
                    {
                        xtype: 'form',
                        layout: 'form',
                        id: 'login_form',
                        bodyPadding: 5,
                        defaultType: 'textfield',
                        items: [
                            {
                                fieldLabel: 'Login',
                                xtype: 'textfield',
                                name: 'login',
                                minLength: 6,
                                maxLength: 255,
                                maskRe: /^[0-9a-z_.]+$/i,
                                regex: /^[0-9a-z_.]+$/i,
                                regexText: 'Login może składać się z liter, cyfr i znaków kropki oraz podkreślenia',
                                allowBlank: false,
                                inputWidth: 200
                            },
                            {
                                fieldLabel: 'Hasło',
                                name: 'pass',
                                minLength: 6,
                                allowBlank: false,
                                inputType: 'password',
                                inputWidth: 200
                            }
                        ],
                        defaults: {
                            width: 300,
                            labelWidth: 100
                        },
                        bbar: [
                            {
                                xtype: 'button',
                                text: 'Zaloguj',
                                handler: function() {
                                    if (Ext.getCmp('login_form').getForm().isValid()) {
                                        var loginFormFields = Ext.getCmp('login_form').getValues();
                                        Ext.Ajax.request({
                                            url: URLPrefix +'/Service/user',
                                            method: 'post',
                                            params: {
                                                login: loginFormFields.login,
                                                pass: loginFormFields.pass,
                                                command: 'login'
                                            },
                                            success: function(response) {
                                                var resp = Ext.JSON.decode(response.responseText, true);
                                                if (!resp) {
                                                    Ext.Msg.alert('Błąd', "Brak odpowiedzi z serwera!");
                                                    return;
                                                }
                                                if (resp.success == false)
                                                {
                                                    Ext.Msg.alert('Błąd', "Logowanie nie powiodło się");
                                                    return;
                                                }
                                                location.href = 'Panel';
                                            }
                                        });
                                    }
                                }
                            },
                            {
                                xtype: 'button',
                                text: 'Rejestracja użytkownika',
                                handler: function() {
                                    Ext.create('Ext.window.Window', {
                                        layout: 'fit',
                                        title: 'Rejestracja użytkownika',
                                        id: 'new_user_window',
                                        items: [{
                                            xtype: 'form',
                                            id: 'new_user_form',
                                            bodyPadding: 10,
                                            items: [
                                                {
                                                    fieldLabel: 'Login',
                                                    xtype: 'textfield',
                                                    name: 'login',
                                                    minLength: 6,
                                                    maxLength: 255,
                                                    maskRe: /^[0-9a-z_.]+$/i,
                                                    regex: /^[0-9a-z_.]+$/i,
                                                    regexText: 'Login może składać się z liter, cyfr i znaków kropki oraz podkreślenia',
                                                    allowBlank: false,
                                                    inputWidth: 200
                                                },
                                                {
                                                    fieldLabel: 'Hasło',
                                                    xtype: 'textfield',
                                                    name: 'pass',
                                                    minLength: 6,
                                                    allowBlank: false,
                                                    inputType: 'password',
                                                    inputWidth: 200
                                                },
                                                {
                                                    fieldLabel: 'Powtórz hasło',
                                                    xtype: 'textfield',
                                                    name: 'pass2',
                                                    minLength: 6,
                                                    allowBlank: false,
                                                    inputType: 'password',
                                                    inputWidth: 200
                                                },
                                                {
                                                    fieldLabel: 'E-mail',
                                                    xtype: 'textfield',
                                                    name: 'email',
                                                    minLength: 6,
                                                    allowBlank: false,
                                                    inputWidth: 200
                                                }
                                            ],
                                            bbar: [
                                                {
                                                    xtype: 'button',
                                                    text: 'Utwórz konto',
                                                    handler: function() {
                                                        var form = Ext.getCmp('new_user_form');
                                                        var values = form.getValues();
                                                        if (values.pass !== values.pass2) {
                                                            var fields = form.getForm().getFields();
                                                            var fieldIndex = fields.findIndex('name', 'pass2');
                                                            var invalidField = fields.getAt(fieldIndex);
                                                            invalidField.markInvalid("Podane hasła się nie zgadzają");
                                                            return;
                                                        }
                                                        if(form.getForm().isValid()) {
                                                            form.getForm().submit({
                                                                url: URLPrefix + '/Service/user',
                                                                method: 'post',
                                                                params: {
                                                                    command: 'create'
                                                                },
                                                                success: function(form, action) {
                                                                    var resp = Ext.JSON.decode(action.response.responseText, true);
                                                                    if (!resp) {
                                                                        Ext.Msg.alert('Błąd', "Brak odpowiedzi z serwera!");
                                                                        return;
                                                                    }
                                                                    if (resp.success == false)
                                                                    {
                                                                        Ext.Msg.alert('Błąd', resp.msg);
                                                                        return;
                                                                    }
                                                                    Ext.Msg.alert('Komunikat', "Konto zostało utworzone. Teraz możesz się zalogować.");
                                                                    Ext.getCmp('new_user_window').close();
                                                                },
                                                                failure: function(form, action) {
                                                                    var resp = Ext.JSON.decode(action.response.responseText, true);
                                                                    if (!resp) {
                                                                        Ext.Msg.alert('Błąd', "Brak odpowiedzi z serwera!");
                                                                        return;
                                                                    }
                                                                    Ext.Msg.alert('Błąd', resp.msg);
                                                                    return;
                                                                }
                                                            })
                                                        }
                                                    }
                                                }
                                            ]
                                        }]
                                    }).show();
                                }
                            }
                        ] 
                    }
                ]
            });
        });
    </script>
</body>
</html>
