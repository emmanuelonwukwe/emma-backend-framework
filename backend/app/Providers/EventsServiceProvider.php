<?php
    namespace App\Providers;

    use App\Events\SendDepositEmail;
    use App\Listeners\PushDepositEmail;
    use App\Events\SendWithdrawalEmail;
    use App\Listeners\PushWithdrawalEmail;
    use App\Events\SendInvestmentEmail;
    use App\Listeners\PushInvestmentEmail;
    use App\Events\SendRegisterationEmail;
    use App\Listeners\PushRegisterationEmail;
    use App\Events\SendLoginEmail;
    use App\Listeners\PushLoginEmail;
    use App\Events\SendResetPasswordEmail;
    use App\Listeners\PushResetPasswordEmail;

    class EventsServiceProvider {
        /**
         * Register all your events and listeners here to be dispatched 
         * [event => [listener1, listener2, ...]]
         */
        public static $listeners = [
            SendDepositEmail::class => [PushDepositEmail::class],
            SendWithdrawalEmail::class => [PushWithdrawalEmail::class],
            SendInvestmentEmail::class => [PushInvestmentEmail::class],
            SendRegisterationEmail::class => [PushRegisterationEmail::class],
            SendLoginEmail::class => [PushLoginEmail::class],
            SendResetPasswordEmail::class => [PushResetPasswordEmail::class],
        ];
    }
?>