<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use common\models\User;
use common\models\MailFile;

$this->title = 'Mail';

$this->registerJs('

    function pasteTo() {
        var toline = \'\';

        for (var i = 0; i < $(\'[name=user-ids]\').length; i++) {
            if ($($(\'[name=user-ids]\')[i]).is(\':checked\')) {
                if (toline == \'\') {
                    toline = toline + $(\'[name=user-ids]\')[i].value;
                } else {
                    toline = toline + \', \' + $(\'[name=user-ids]\')[i].value;
                }
            }
        }

        console.log(toline);

        $(\'#mailform-to\').val(toline);
    }



    $(document).ready(function(){
        pasteTo();
    });






    $(document).on(\'change\', \'.select-sample\', function(){
        val = $(this).find(\':selected\').attr(\'data-sample\');
        console.log(val);

        text = $(\'.mail-sample.mail-sample-\' + val).text();

        $(\'.mail-sample\').addClass(\'hidden\');
        $(\'.mail-sample.mail-sample-\' + val).removeClass(\'hidden\');

        if ($(\'.fill-by-select\').val().length == 0) {
            $(\'.fill-by-select\').val(text);
        }
    });

    $(document).on(\'click\', \'.btn-paste\', function(){
        val = $(\'.select-sample\').find(\':selected\').attr(\'data-sample\');
        text = $(\'.mail-sample.mail-sample-\' + val).text();

        if (confirm(\'Paste template/sample text to message textarea?\')) {
            $(\'.fill-by-select\').val(text).attr(\'rows\', 15);
        }
    });

    $(document).on(\'click\', \'.label-dep\', function(){
        val = $(this).attr(\'data-dep\');
        $(\'.label-dep.label-primary\').removeClass(\'label-primary, label-grey\').addClass(\'label-grey\');
        $(this).removeClass(\'label-grey\').addClass(\'label-primary\');
        $(\'.input-from\').val(val);
    });





    // $(document).on(\'click\', \'[name=lot-type-none]\', function(){
    //     $(\'.wrap-label[data-active] input\').attr(\'checked\', $(this).is(\':checked\'));
    // });

    $(document).on(\'click\', \'[name=lot-type-staff]\', function(){
        $(\'.wrap-label[data-staff="1"] input\').attr(\'checked\', $(this).is(\':checked\'));
    });

    $(document).on(\'click\', \'[name=lot-type-active]\', function(){
        $(\'.wrap-label[data-active="1"] input\').attr(\'checked\', $(this).is(\':checked\'));
    });

    $(document).on(\'click\', \'[name=lot-type-inactive]\', function(){
        $(\'.wrap-label[data-active="0"] input\').attr(\'checked\', $(this).is(\':checked\'));
    });

    $(document).on(\'click\', \'[name^=lot-type]\', function(){
        pasteTo();
    });


    $(document).on(\'click\', \'[name=user-ids]\', function(){
        pasteTo();
    });

');

$newMail = MailFile::find()->where(['status' => 1])->count();

?>





<style type="text/css">
textarea {
    padding-top: 12px!important;
}
.help-block {
    padding-left: 17px;
    font-size: 14px;
}
.label-dep {
    cursor: pointer;
    margin-right: 5px;
}
.label-grey, .btn-grey {
    background-color: #eee;
    color: #668;
}
.wrap-label[data-active="0"] {
    opacity: .6;
}

.wrap-filter label, .wrap-label label {
    font-weight: 400;
    padding: 5px 7px;
    cursor: pointer;
}

.wrap-label label {
    width: 100%;
}
.wrap-label label:hover {
    background: #f5f5e5;
}
.wrap-label label:hover {
    color: #08e;
}
.wrap-label input[type=checkbox], .wrap-filter input[type=checkbox] {
    position: relative;
    margin: 0 4px 0 0;
    top: 2px;
}
.btn-inbox {
    position: absolute;
    top: -5px;
    right: 0;
    text-transform: none;
}
.wrap-mail-from {
    width: 40%; float: left
}
.wrap-address {
    width: 17.5%; margin-left: 5%; margin-right: 2.5%; float: left
}
.wrap-template {
    width: 35%; float: left
}
@media (max-width: 768px) {
    .wrap-mail-from {
        width: 100%;
    }
    .wrap-address {
        width: 100%;
    }
    .wrap-template {
        width: 100%;
        margin-top: 60px;
    }
}
</style>





<div class="site-contact">


    <div class="wrap-mail-from" style="">

    	<h3 style="position: relative; margin-bottom: 15px;">
            <a class="btn btn-grey btn-inbox" href="/mail/inbox">inbox <span>(new: <?php echo $newMail; ?>)</span></a>
            <span>Mailing</span>
        </h3><br/>


        <div>

            <div>
                <strong class="" style="margin-right: 5px">From:</strong>
                <span class="label label-dep label-primary" data-dep="info@damfz.co">info</span>
                <span class="label label-dep label-grey" data-dep="contact@damfz.co">contact</span>
                <span class="label label-dep label-grey" data-dep="support@damfz.co">support</span>
                <span class="label label-dep label-grey" data-dep="promo@damfz.co">promo</span>
            </div><br/><br/><br/>


            <?php $form = ActiveForm::begin(['options' => ['id' => 'contact-form', 'enctype' => 'multipart/form-data']]); ?>

                <?= $form->field($model, 'from')->textInput([
                        'placeholder' => 'Email', 
                        'class' => 'form-control input-from', 
                        'value' => 'info@damfz.co',
                        'readonly' => true,
                        // 'value' => strtolower(User::findIdentity(Yii::$app->user->getId())->name_first) . '@damfz.co',
                    ])->label(false) 
                ?>
                <?= $form->field($model, 'to')->textInput([
                        'placeholder' => 'one@email.com, two@email.com, ...', 
                        'class' => 'form-control', 
                        'value' => '',
                        // 'value' => User::findIdentity(Yii::$app->user->getId())->email,
                        // 'readonly' => true,
                    ])->label('Recipients: (delimiter is ", ")') 
                ?>
                <?= $form->field($model, 'name')->textInput([
                    'placeholder' => 'Your Name', 
                    'class' => 'form-control', 
                    // 'value' => User::findIdentity(Yii::$app->user->getId())->name_first ?? '',
                ])->label('Your Name:') ?>

                <?= $form->field($model, 'subject')->textInput(['placeholder' => 'Define Subject', 'class' => 'form-control'])->label('Subject:') ?>
                <?= $form->field($model, 'body')->textarea(['rows' => 7, 'placeholder' => 'Write down your message...', 'class' => 'form-control fill-by-select'])->label('Message:') ?>

                <?= $form->field($model, 'attachments[]')->fileInput([
                    'multiple' => 'multiple',
                    'id' => 'attachment',
                    'required' => false,
                ]) ?>

                <br/><br/>

                <div class="form-group">
                    <?= Html::submitButton('Send Message', [
                            'onclick' => 'if (!confirm(\'Please, check recipients twice - is that ok?\')) return false;',
                            'class' => 'btn btn-success', 
                            'name' => 'contact-button',
                    ]) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>

        <?php //var_dump($data); ?>

    </div>



    <div class="wrap-address" style="">
        <h3>Recipients</h3><br/>

        <!-- <div class="pull-left" style="margin-bottom: 30px">
            <select class="form-control send-type pull-left" style="width: 185px">
                <option data-type="all">All customers</option>
                <option data-type="lot">Selectively</option>
            </select>
        </div> -->

        <div class="pull-left" style="width: 100%; margin-left: -5px">
            <div class="wrap-filter">
                <!-- <label> <input name="lot-type-none" type="checkbox" /> All/None </label> -->
                <label> <input name="lot-type-staff" type="checkbox" /> Staff </label>
                <label> <input name="lot-type-active" type="checkbox" /> Active </label>
                <label> <input name="lot-type-inactive" type="checkbox" /> In-active </label>
            </div>
        </div>

        <br/><br/><br/><br/>

        <div class="pull-left" style="width: 100%; margin-left: -5px; height: 550px; overflow: auto">
            <?php foreach ($users as $user) : ?>
                <div class="wrap-label">
                    <label>
                        <input value="<?= strtolower($user->email) ?>"name="user-ids"type="checkbox"/>
                        <span><?= strtolower($user->email) ?></span>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

















    <div class="wrap-template" style="">
        <h3>Mail Template/Sample</h3><br/>

        <select class="form-control select-sample pull-left" style="width: 185px">
            <option data-sample="tfa">2FA</option>
            <option data-sample="tfa-signin">2FA sign-in issue</option>
            <option data-sample="marketing">Marketing</option>
            <option data-sample="accrual">Accrual</option>
        </select>

        <span class="btn btn-link btn-sm text-muted btn-paste" style="margin: 1px 0 0 15px">paste to textarea</span>

        <br/><br/>

        <div class="mail-sample mail-sample-marketing hidden" style="font-size:13px; color: #aab; padding: 15px 20% 15px 5px;white-space: pre-wrap">
Re: Здравствуйте подскажите раздел рекламные материалы планируется?
...

Здравствуйте, 
Спасибо, что связались с нами, мы всегда рады помочь!

Если ваш вопрос касается {smth ...} , то ответ - да!) 
Мы рады, что Вы задали этот вопрос, и хотели бы {smth ...}

Также, если не вызовет затруднений, могли бы Вы ответить на два вопроса?

1. Где бы Вы хотели разместить рекламные материалы?
а) Доступный по сети (www) ресурс (пожалуйста, уточните характер ресурса: форум, новостной ресурс, агрегатор контента, соц. сети, блог/видеоблог и т.д.)
б) Мобильное приложение (уточните адрес приложения) 
в) Медиа источники (не www) (уточните сферу: газета, ТВ, радио и т.д.)
г) Мероприятие/коференция (пожалуйста, уточните название мероприятия и дату)

2. Могли бы Вы немного рассказать о вашей специализации и сфере занятости? Чтобы у нас появилась возможность провести анализ и сформировать максимально эффективное взаимодействие.

Мы надеемся, что сможем Вам помочь в любом случае, поэтому хотели бы, чтобы наш диалог продолжился, а Вы ответили на это письмо. Возможно, получится что-то решить в рамках вашего вопроса индивидуально, если {smth ...}

С уважением,
команда iCrypto.world
        </div>

        <div class="mail-sample mail-sample-tfa" style="font-size:13px; color: #aab; padding: 15px 20% 15px 5px;white-space: pre-wrap">
Re: Показывает что 2FA-код не верный
...

Здравствуйте, 
Спасибо, что связались с нами, мы всегда рады помочь! 

Пожалуйста, сообщите адрес страницы и опишите при каких событиях происходит отказ по 2FA-коду.

Мы надеемся, что сможем Вам помочь в любом случае, поэтому хотели бы, чтобы наш диалог продолжился, а Вы ответили на это письмо.

С уважением,
команда iCrypto.world
        </div>

        <div class="mail-sample mail-sample-tfa-signin hidden" style="font-size:13px; color: #aab; padding: 15px 20% 15px 5px;white-space: pre-wrap">
Re: please reset my 2FA need to withdraw fund and its not working
...

Hello, {name},
We're glad you've contacted us & ready to help!

The subject of the ticket defines your 2FA-key is stale or lost and you cannot sign into the system, - <u>could you approve that is right</u>?

<u>If so</u>, you will be required to provide us your current phone number, to which we shall send one-time-password message (sms).

<u>Else</u> (sign-in-action 2FA-requirement is off), below is applicable:

To reset your 2FA-key, we require to re-verify your identity, to do so we will remove your phone-number record, so you could add & verify your current one (by sms one-time-password).

<u>Could you, also</u>, help us a bit, and tell within what conditions your 2FA-code is denied?
Also, please, recall a precise URL of the action-page.

Sometimes, our clients are a bit confused about our graphical user interface or experience, - so we try to pull out any helpful information on any case or issue of the clients.

Cordially,
iCrypto.world team.
        </div>

        <div class="mail-sample mail-sample-accrual hidden" style="font-size:13px; color: #aab; padding: 15px 20% 15px 5px;white-space: pre-wrap">
Re: Здравствуйте купила два дня назад мощность а начислений так и не. можнте помочь разобраться?
...

Здравствуйте, 
Спасибо, что связались с нами, мы всегда рады помочь!

Если ваш вопрос касается кластера для облачного клиентского майнинга, - приносим извинения за длительные задержки, настройка оборудования может занимать до 48-и часов и еще 24+ могут уйти на то чтобы оборудование наработало первый вклад в нахождение блока, с которого будет получена награда начисляемая Вам.

Мы просим Вас зайти в личный кабинет и проверить начисления еще раз.

Также, если не вызовет затруднений, могли бы Вы ответить на два вопроса?

1. Могли бы Вы немного рассказать, каким образом Вы получили код-приглашение в данный проект? Чтобы мы смогли проанализировать рациональность нашей продуктовой политики и появилась дополнительная возможность сформировать максимально эффективное взаимодействие с нашими клиентами.

2. Могли бы Вы немного рассказать о вашей специализации и сфере занятости?

Мы надеемся, что сможем Вам помочь в любом случае, поэтому хотели бы, чтобы наш диалог продолжился, а Вы ответили на это письмо. Мы абсолютно уверены, что это невозможно, но если задержка с даты приобритения мощностей до первого начисления составляет более 84 часов, то, пожалуйста, сообщите нам об этом, - мы обязательно постараемся все решить.

С уважением,
команда iCrypto.world
        
        </div>
    </div>

</div>



