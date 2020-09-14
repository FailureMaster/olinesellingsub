<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1efa9256d5eb097a36f265d36bd6c0dc
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
        'M' => 
        array (
            'Monolog\\' => 8,
        ),
        'A' => 
        array (
            'Adyen\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'Monolog\\' => 
        array (
            0 => __DIR__ . '/..' . '/monolog/monolog/src/Monolog',
        ),
        'Adyen\\' => 
        array (
            0 => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen',
        ),
    );

    public static $classMap = array (
        'Adyen\\Service\\AbstractCheckoutResource' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/AbstractCheckoutResource.php',
        'Adyen\\Service\\AbstractResource' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/AbstractResource.php',
        'Adyen\\Service\\Account' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/Account.php',
        'Adyen\\Service\\BinLookup' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/BinLookup.php',
        'Adyen\\Service\\Checkout' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/Checkout.php',
        'Adyen\\Service\\CheckoutUtility' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/CheckoutUtility.php',
        'Adyen\\Service\\DirectoryLookup' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/DirectoryLookup.php',
        'Adyen\\Service\\Fund' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/Fund.php',
        'Adyen\\Service\\Modification' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/Modification.php',
        'Adyen\\Service\\Notification' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/Notification.php',
        'Adyen\\Service\\Payment' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/Payment.php',
        'Adyen\\Service\\Payout' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/Payout.php',
        'Adyen\\Service\\PosPayment' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/PosPayment.php',
        'Adyen\\Service\\Recurring' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/Recurring.php',
        'Adyen\\Service\\ResourceModel\\Account\\CloseAccount' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Account/CloseAccount.php',
        'Adyen\\Service\\ResourceModel\\Account\\CloseAccountHolder' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Account/CloseAccountHolder.php',
        'Adyen\\Service\\ResourceModel\\Account\\CreateAccount' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Account/CreateAccount.php',
        'Adyen\\Service\\ResourceModel\\Account\\CreateAccountHolder' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Account/CreateAccountHolder.php',
        'Adyen\\Service\\ResourceModel\\Account\\DeleteBankAccounts' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Account/DeleteBankAccounts.php',
        'Adyen\\Service\\ResourceModel\\Account\\DeleteShareholders' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Account/DeleteShareholders.php',
        'Adyen\\Service\\ResourceModel\\Account\\GetAccountHolder' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Account/GetAccountHolder.php',
        'Adyen\\Service\\ResourceModel\\Account\\GetUploadedDocuments' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Account/GetUploadedDocuments.php',
        'Adyen\\Service\\ResourceModel\\Account\\SuspendAccountHolder' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Account/SuspendAccountHolder.php',
        'Adyen\\Service\\ResourceModel\\Account\\UnSuspendAccountHolder' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Account/UnSuspendAccountHolder.php',
        'Adyen\\Service\\ResourceModel\\Account\\UpdateAccount' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Account/UpdateAccount.php',
        'Adyen\\Service\\ResourceModel\\Account\\UpdateAccountHolder' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Account/UpdateAccountHolder.php',
        'Adyen\\Service\\ResourceModel\\Account\\UpdateAccountHolderState' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Account/UpdateAccountHolderState.php',
        'Adyen\\Service\\ResourceModel\\Account\\UploadDocument' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Account/UploadDocument.php',
        'Adyen\\Service\\ResourceModel\\BinLookup\\Get3dsAvailability' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/BinLookup/Get3dsAvailability.php',
        'Adyen\\Service\\ResourceModel\\BinLookup\\GetCostEstimate' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/BinLookup/GetCostEstimate.php',
        'Adyen\\Service\\ResourceModel\\CheckoutUtility\\OriginKeys' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/CheckoutUtility/OriginKeys.php',
        'Adyen\\Service\\ResourceModel\\Checkout\\PaymentMethods' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Checkout/PaymentMethods.php',
        'Adyen\\Service\\ResourceModel\\Checkout\\PaymentSession' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Checkout/PaymentSession.php',
        'Adyen\\Service\\ResourceModel\\Checkout\\Payments' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Checkout/Payments.php',
        'Adyen\\Service\\ResourceModel\\Checkout\\PaymentsDetails' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Checkout/PaymentsDetails.php',
        'Adyen\\Service\\ResourceModel\\Checkout\\PaymentsResult' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Checkout/PaymentsResult.php',
        'Adyen\\Service\\ResourceModel\\DirectoryLookup\\Directory' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/DirectoryLookup/Directory.php',
        'Adyen\\Service\\ResourceModel\\Fund\\AccountHolderBalance' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Fund/AccountHolderBalance.php',
        'Adyen\\Service\\ResourceModel\\Fund\\AccountHolderTransactionList' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Fund/AccountHolderTransactionList.php',
        'Adyen\\Service\\ResourceModel\\Fund\\PayoutAccountHolder' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Fund/PayoutAccountHolder.php',
        'Adyen\\Service\\ResourceModel\\Fund\\RefundNotPaidOutTransfers' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Fund/RefundNotPaidOutTransfers.php',
        'Adyen\\Service\\ResourceModel\\Fund\\SetupBeneficiary' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Fund/SetupBeneficiary.php',
        'Adyen\\Service\\ResourceModel\\Fund\\TransferFunds' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Fund/TransferFunds.php',
        'Adyen\\Service\\ResourceModel\\Modification\\AdjustAuthorisation' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Modification/AdjustAuthorisation.php',
        'Adyen\\Service\\ResourceModel\\Modification\\Cancel' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Modification/Cancel.php',
        'Adyen\\Service\\ResourceModel\\Modification\\CancelOrRefund' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Modification/CancelOrRefund.php',
        'Adyen\\Service\\ResourceModel\\Modification\\Capture' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Modification/Capture.php',
        'Adyen\\Service\\ResourceModel\\Modification\\Refund' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Modification/Refund.php',
        'Adyen\\Service\\ResourceModel\\Modification\\RefundWithData' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Modification/RefundWithData.php',
        'Adyen\\Service\\ResourceModel\\Notification\\CreateNotificationConfiguration' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Notification/CreateNotificationConfiguration.php',
        'Adyen\\Service\\ResourceModel\\Notification\\DeleteNotificationConfigurations' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Notification/DeleteNotificationConfigurations.php',
        'Adyen\\Service\\ResourceModel\\Notification\\GetNotificationConfiguration' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Notification/GetNotificationConfiguration.php',
        'Adyen\\Service\\ResourceModel\\Notification\\GetNotificationConfigurationList' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Notification/GetNotificationConfigurationList.php',
        'Adyen\\Service\\ResourceModel\\Notification\\TestNotificationConfiguration' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Notification/TestNotificationConfiguration.php',
        'Adyen\\Service\\ResourceModel\\Notification\\UpdateNotificationConfiguration' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Notification/UpdateNotificationConfiguration.php',
        'Adyen\\Service\\ResourceModel\\Payment\\Authorise' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Payment/Authorise.php',
        'Adyen\\Service\\ResourceModel\\Payment\\Authorise3D' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Payment/Authorise3D.php',
        'Adyen\\Service\\ResourceModel\\Payment\\Authorise3DS2' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Payment/Authorise3DS2.php',
        'Adyen\\Service\\ResourceModel\\Payment\\ConnectedTerminals' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Payment/ConnectedTerminals.php',
        'Adyen\\Service\\ResourceModel\\Payment\\TerminalCloudAPI' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Payment/TerminalCloudAPI.php',
        'Adyen\\Service\\ResourceModel\\Payout\\Confirm' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Payout/Confirm.php',
        'Adyen\\Service\\ResourceModel\\Payout\\Decline' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Payout/Decline.php',
        'Adyen\\Service\\ResourceModel\\Payout\\StoreDetailsAndSubmit' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Payout/StoreDetailsAndSubmit.php',
        'Adyen\\Service\\ResourceModel\\Payout\\Submit' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Payout/Submit.php',
        'Adyen\\Service\\ResourceModel\\Payout\\ThirdParty\\ConfirmThirdParty' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Payout/ThirdParty/ConfirmThirdParty.php',
        'Adyen\\Service\\ResourceModel\\Payout\\ThirdParty\\DeclineThirdParty' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Payout/ThirdParty/DeclineThirdParty.php',
        'Adyen\\Service\\ResourceModel\\Payout\\ThirdParty\\StoreDetail' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Payout/ThirdParty/StoreDetail.php',
        'Adyen\\Service\\ResourceModel\\Payout\\ThirdParty\\StoreDetailsAndSubmitThirdParty' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Payout/ThirdParty/StoreDetailsAndSubmitThirdParty.php',
        'Adyen\\Service\\ResourceModel\\Payout\\ThirdParty\\SubmitThirdParty' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Payout/ThirdParty/SubmitThirdParty.php',
        'Adyen\\Service\\ResourceModel\\Recurring\\Disable' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Recurring/Disable.php',
        'Adyen\\Service\\ResourceModel\\Recurring\\ListRecurringDetails' => __DIR__ . '/..' . '/adyen/php-api-library/src/Adyen/Service/ResourceModel/Recurring/ListRecurringDetails.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1efa9256d5eb097a36f265d36bd6c0dc::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1efa9256d5eb097a36f265d36bd6c0dc::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit1efa9256d5eb097a36f265d36bd6c0dc::$classMap;

        }, null, ClassLoader::class);
    }
}