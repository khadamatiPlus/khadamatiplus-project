<?php
/**
 * Created by Omar
 * Author: HUDHUD IT
 * On: 5/8/2023
 * Class: hyperpay_result_codes.php
 */
return [
    [
        "code" => "000.000.000",
        "description" => "العملية تمت بنجاح (مدفوع)"
    ],
    [
        "code" => "000.000.100",
        "description" => "successful request"
    ],
    [
        "code" => "000.100.110",
        "description" => "Request successfully processed in 'Merchant in Integrator Test Mode'"
    ],
    [
        "code" => "000.100.111",
        "description" => "Request successfully processed in 'Merchant in Validator Test Mode'"
    ],
    [
        "code" => "000.100.112",
        "description" => "تمت معالجة الطلب بنجاح في \"وضع التجربة\" / مدفوع"
    ],
    [
        "code" => "000.100.200",
        "description" => "Reason not Specified"
    ],
    [
        "code" => "000.100.201",
        "description" => "Account or Bank Details Incorrect"
    ],
    [
        "code" => "000.100.202",
        "description" => "Account Closed"
    ],
    [
        "code" => "000.100.203",
        "description" => "Insufficient Funds"
    ],
    [
        "code" => "000.100.204",
        "description" => "Mandate not Valid"
    ],
    [
        "code" => "000.100.205",
        "description" => "Mandate Cancelled"
    ],
    [
        "code" => "000.100.206",
        "description" => "Revocation or Dispute"
    ],
    [
        "code" => "000.100.207",
        "description" => "Cancellation in Clearing Network"
    ],
    [
        "code" => "000.100.208",
        "description" => "Account Blocked"
    ],
    [
        "code" => "000.100.209",
        "description" => "Account does not exist"
    ],
    [
        "code" => "000.100.210",
        "description" => "Invalid Amount"
    ],
    [
        "code" => "000.100.211",
        "description" => "Transaction succeeded (amount of transaction is smaller then amount of pre-authorization)"
    ],
    [
        "code" => "000.100.212",
        "description" => "Transaction succeeded (amount of transaction is greater then amount of pre-authorization)"
    ],
    [
        "code" => "000.100.220",
        "description" => "Fraudulent Transaction"
    ],
    [
        "code" => "000.100.221",
        "description" => "Merchandise Not Received"
    ],
    [
        "code" => "000.100.222",
        "description" => "Transaction Not Recognized By Cardholder"
    ],
    [
        "code" => "000.100.223",
        "description" => "Service Not Rendered"
    ],
    [
        "code" => "000.100.224",
        "description" => "Duplicate Processing"
    ],
    [
        "code" => "000.100.225",
        "description" => "Credit Not Processed"
    ],
    [
        "code" => "000.100.226",
        "description" => "Cannot be settled"
    ],
    [
        "code" => "000.100.227",
        "description" => "Configuration Issue"
    ],
    [
        "code" => "000.100.228",
        "description" => "Temporary Communication Error - Retry"
    ],
    [
        "code" => "000.100.229",
        "description" => "Incorrect Instructions"
    ],
    [
        "code" => "000.100.230",
        "description" => "Unauthorised Charge"
    ],
    [
        "code" => "000.100.299",
        "description" => "Unspecified (Technical)"
    ],
    [
        "code" => "000.200.000",
        "description" => "الدفع قيد الإنتظار / غير مدفوع"
    ],
    [
        "code" => "000.200.001",
        "description" => "Transaction pending for acquirer, the consumer is not present"
    ],
    [
        "code" => "000.200.100",
        "description" => "تم إنشاء طلب الدفع بنجاح / غير مدفوع"
    ],
    [
        "code" => "000.200.101",
        "description" => "successfully updated checkout"
    ],
    [
        "code" => "000.200.102",
        "description" => "successfully deleted checkout"
    ],
    [
        "code" => "000.200.103",
        "description" => "checkout is pending"
    ],
    [
        "code" => "000.200.200",
        "description" => "Transaction initialized"
    ],
    [
        "code" => "000.300.000",
        "description" => "Two-step transaction succeeded"
    ],
    [
        "code" => "000.300.100",
        "description" => "Risk check successful"
    ],
    [
        "code" => "000.300.101",
        "description" => "Risk bank account check successful"
    ],
    [
        "code" => "000.300.102",
        "description" => "Risk report successful"
    ],
    [
        "code" => "000.310.100",
        "description" => "Account updated"
    ],
    [
        "code" => "000.310.101",
        "description" => "Account updated (Credit card expired)"
    ],
    [
        "code" => "000.310.110",
        "description" => "No updates found, but account is valid"
    ],
    [
        "code" => "000.400.000",
        "description" => "Transaction succeeded (please review manually due to fraud suspicion)"
    ],
    [
        "code" => "000.400.010",
        "description" => "Transaction succeeded (please review manually due to AVS return code)"
    ],
    [
        "code" => "000.400.020",
        "description" => "Transaction succeeded (please review manually due to CVV return code)"
    ],
    [
        "code" => "000.400.030",
        "description" => "Transaction partially failed (please reverse manually due to failed automatic reversal)"
    ],
    [
        "code" => "000.400.040",
        "description" => "Transaction succeeded (please review manually due to amount mismatch)"
    ],
    [
        "code" => "000.400.050",
        "description" => "Transaction succeeded (please review manually because transaction is pending)"
    ],
    [
        "code" => "000.400.060",
        "description" => "Transaction succeeded (approved at merchant's risk)"
    ],
    [
        "code" => "000.400.070",
        "description" => "Transaction succeeded (waiting for external risk review)"
    ],
    [
        "code" => "000.400.080",
        "description" => "Transaction succeeded (please review manually because the service was unavailable)"
    ],
    [
        "code" => "000.400.081",
        "description" => "Transaction succeeded (please review manually, as the risk status not available yet due network timeout)"
    ],
    [
        "code" => "000.400.082",
        "description" => "Transaction succeeded (please review manually, as the risk status not available yet due processing timeout)"
    ],
    [
        "code" => "000.400.090",
        "description" => "Transaction succeeded (please review manually due to external risk check)"
    ],
    [
        "code" => "000.400.100",
        "description" => "Transaction succeeded, risk after payment rejected"
    ],
    [
        "code" => "000.400.101",
        "description" => "card not participating/authentication unavailable"
    ],
    [
        "code" => "000.400.102",
        "description" => "user not enrolled"
    ],
    [
        "code" => "000.400.103",
        "description" => "Technical Error in 3D system"
    ],
    [
        "code" => "000.400.104",
        "description" => "Missing or malformed 3DSecure Configuration for Channel"
    ],
    [
        "code" => "000.400.105",
        "description" => "Unsupported User Device - Authentication not possible"
    ],
    [
        "code" => "000.400.106",
        "description" => "invalid payer authentication response(PARes) in 3DSecure Transaction"
    ],
    [
        "code" => "000.400.107",
        "description" => "Communication Error to VISA/Mastercard Directory Server"
    ],
    [
        "code" => "000.400.108",
        "description" => "Cardholder Not Found - card number provided is not found in the ranges of the issuer"
    ],
    [
        "code" => "000.400.109",
        "description" => "Card is not enrolled for 3DS version 2"
    ],
    [
        "code" => "000.400.110",
        "description" => "Authentication successful (frictionless flow)"
    ],
    [
        "code" => "000.400.200",
        "description" => "risk management check communication error"
    ],
    [
        "code" => "000.500.000",
        "description" => "Transaction succeeded - very good rating"
    ],
    [
        "code" => "000.500.100",
        "description" => "Transaction succeeded (address corrected)"
    ],
    [
        "code" => "000.600.000",
        "description" => "transaction succeeded due to external update"
    ],
    [
        "code" => "100.100.100",
        "description" => "request contains no creditcard, bank account number or bank name"
    ],
    [
        "code" => "100.100.101",
        "description" => "بطاقة ائتمان أو رقم حساب مصرفي أو اسم البنك غير صالح / غير مدفوع"
    ],
    [
        "code" => "100.100.104",
        "description" => "invalid unique id / root unique id"
    ],
    [
        "code" => "100.100.200",
        "description" => "request contains no month"
    ],
    [
        "code" => "100.100.201",
        "description" => "invalid month"
    ],
    [
        "code" => "100.100.300",
        "description" => "request contains no year"
    ],
    [
        "code" => "100.100.301",
        "description" => "invalid year"
    ],
    [
        "code" => "100.100.303",
        "description" => "card expired"
    ],
    [
        "code" => "100.100.304",
        "description" => "card not yet valid"
    ],
    [
        "code" => "100.100.305",
        "description" => "invalid expiration date format"
    ],
    [
        "code" => "100.100.400",
        "description" => "request contains no cc/bank account holder"
    ],
    [
        "code" => "100.100.401",
        "description" => "cc/bank account holder too short or too long"
    ],
    [
        "code" => "100.100.402",
        "description" => "cc/bank account holder not valid"
    ],
    [
        "code" => "100.100.500",
        "description" => "لا يحتوي الطلب على علامة تجارية لبطاقة الائتمان / غير مدفوع"
    ],
    [
        "code" => "100.100.501",
        "description" => "invalid credit card brand"
    ],
    [
        "code" => "100.100.600",
        "description" => "empty CVV for VISA,MASTER, AMEX not allowed"
    ],
    [
        "code" => "100.100.601",
        "description" => "invalid CVV/brand combination"
    ],
    [
        "code" => "100.100.650",
        "description" => "empty CreditCardIssueNumber for MAESTRO not allowed"
    ],
    [
        "code" => "100.100.651",
        "description" => "invalid CreditCardIssueNumber"
    ],
    [
        "code" => "100.100.700",
        "description" => "تركيبة رقم / علامة تجارية غير صالحة / غير مدفوع"
    ],
    [
        "code" => "100.100.701",
        "description" => "suspecting fraud, this card may not be processed"
    ],
    [
        "code" => "100.150.100",
        "description" => "request contains no Account data and no registration id"
    ],
    [
        "code" => "100.150.101",
        "description" => "invalid format for specified registration id (must be uuid format)"
    ],
    [
        "code" => "100.150.200",
        "description" => "registration does not exist"
    ],
    [
        "code" => "100.150.201",
        "description" => "registration is not confirmed yet"
    ],
    [
        "code" => "100.150.202",
        "description" => "registration is already deregistered"
    ],
    [
        "code" => "100.150.203",
        "description" => "registration is not valid, probably initially rejected"
    ],
    [
        "code" => "100.150.204",
        "description" => "account registration reference pointed to no registration transaction"
    ],
    [
        "code" => "100.150.205",
        "description" => "referenced registration does not contain an account"
    ],
    [
        "code" => "100.150.300",
        "description" => "payment only allowed with valid initial registration"
    ],
    [
        "code" => "100.200.100",
        "description" => "bank account contains no or invalid country"
    ],
    [
        "code" => "100.200.103",
        "description" => "bank account has invalid bankcode/name account number combination"
    ],
    [
        "code" => "100.200.104",
        "description" => "bank account has invalid acccount number format"
    ],
    [
        "code" => "100.200.200",
        "description" => "bank account needs to be registered and confirmed first. Country is mandate based."
    ],
    [
        "code" => "100.210.101",
        "description" => "virtual account contains no or invalid Id"
    ],
    [
        "code" => "100.210.102",
        "description" => "virtual account contains no or invalid brand"
    ],
    [
        "code" => "100.211.101",
        "description" => "user account contains no or invalid Id"
    ],
    [
        "code" => "100.211.102",
        "description" => "user account contains no or invalid brand"
    ],
    [
        "code" => "100.211.103",
        "description" => "no password defined for user account"
    ],
    [
        "code" => "100.211.104",
        "description" => "password does not meet safety requirements (needs 8 digits at least and must contain letters and numbers)"
    ],
    [
        "code" => "100.211.105",
        "description" => "wallet id has to be a valid email address"
    ],
    [
        "code" => "100.211.106",
        "description" => "voucher ids have 32 digits always"
    ],
    [
        "code" => "100.212.101",
        "description" => "wallet account registration must not have an initial balance"
    ],
    [
        "code" => "100.212.102",
        "description" => "wallet account contains no or invalid brand"
    ],
    [
        "code" => "100.212.103",
        "description" => "wallet account payment transaction needs to reference a registration"
    ],
    [
        "code" => "100.250.100",
        "description" => "job contains no execution information"
    ],
    [
        "code" => "100.250.105",
        "description" => "invalid or missing action type"
    ],
    [
        "code" => "100.250.106",
        "description" => "invalid or missing duration unit"
    ],
    [
        "code" => "100.250.107",
        "description" => "invalid or missing notice unit"
    ],
    [
        "code" => "100.250.110",
        "description" => "missing job execution"
    ],
    [
        "code" => "100.250.111",
        "description" => "missing job expression"
    ],
    [
        "code" => "100.250.120",
        "description" => "invalid execution parameters, combination does not conform to standard"
    ],
    [
        "code" => "100.250.121",
        "description" => "invalid execution parameters, hour must be between 0 and 23"
    ],
    [
        "code" => "100.250.122",
        "description" => "invalid execution parameters, minute and seconds must be between 0 and 59"
    ],
    [
        "code" => "100.250.123",
        "description" => "invalid execution parameters, Day of month must be between 1 and 31"
    ],
    [
        "code" => "100.250.124",
        "description" => "invalid execution parameters, month must be between 1 and 12"
    ],
    [
        "code" => "100.250.125",
        "description" => "invalid execution parameters, Day of week must be between 1 and 7"
    ],
    [
        "code" => "100.250.250",
        "description" => "Job tag missing"
    ],
    [
        "code" => "100.300.101",
        "description" => "invalid test mode (please use LIVE or INTEGRATOR_TEST or CONNECTOR_TEST)"
    ],
    [
        "code" => "100.300.200",
        "description" => "transaction id too long"
    ],
    [
        "code" => "100.300.300",
        "description" => "invalid reference id"
    ],
    [
        "code" => "100.300.400",
        "description" => "missing or invalid channel id"
    ],
    [
        "code" => "100.300.401",
        "description" => "missing or invalid sender id"
    ],
    [
        "code" => "100.300.402",
        "description" => "missing or invalid version"
    ],
    [
        "code" => "100.300.501",
        "description" => "invalid response id"
    ],
    [
        "code" => "100.300.600",
        "description" => "invalid or missing user login"
    ],
    [
        "code" => "100.300.601",
        "description" => "invalid or missing user pwd"
    ],
    [
        "code" => "100.300.700",
        "description" => "invalid relevance"
    ],
    [
        "code" => "100.300.701",
        "description" => "invalid relevance for given payment type"
    ],
    [
        "code" => "100.310.401",
        "description" => "Account management type not supported"
    ],
    [
        "code" => "100.310.402",
        "description" => "Account management transaction not allowed in current state"
    ],
    [
        "code" => "100.350.100",
        "description" => "referenced session is REJECTED (no action possible)."
    ],
    [
        "code" => "100.350.101",
        "description" => "referenced session is CLOSED (no action possible)"
    ],
    [
        "code" => "100.350.200",
        "description" => "undefined session state"
    ],
    [
        "code" => "100.350.201",
        "description" => "referencing a registration through reference id is not applicable for this payment type"
    ],
    [
        "code" => "100.350.301",
        "description" => "confirmation (CF) must be registered (RG) first"
    ],
    [
        "code" => "100.350.302",
        "description" => "session already confirmed (CF)"
    ],
    [
        "code" => "100.350.303",
        "description" => "cannot deregister (DR) unregistered account and/or customer"
    ],
    [
        "code" => "100.350.310",
        "description" => "cannot confirm (CF) session via XML"
    ],
    [
        "code" => "100.350.311",
        "description" => "cannot confirm (CF) on a registration passthrough channel"
    ],
    [
        "code" => "100.350.312",
        "description" => "cannot do passthrough on non-internal connector"
    ],
    [
        "code" => "100.350.313",
        "description" => "registration of this type has to provide confirmation url"
    ],
    [
        "code" => "100.350.314",
        "description" => "customer could not be notified of pin to confirm registration (channel)"
    ],
    [
        "code" => "100.350.315",
        "description" => "customer could not be notified of pin to confirm registration (sending failed)"
    ],
    [
        "code" => "100.350.400",
        "description" => "no or invalid PIN (email/SMS/MicroDeposit authentication) entered"
    ],
    [
        "code" => "100.350.500",
        "description" => "unable to obtain personal (virtual) account - most likely no more accounts available"
    ],
    [
        "code" => "100.350.601",
        "description" => "registration is not allowed to reference another transaction"
    ],
    [
        "code" => "100.350.602",
        "description" => "Registration is not allowed for recurring payment migration"
    ],
    [
        "code" => "100.360.201",
        "description" => "unknown schedule type"
    ],
    [
        "code" => "100.360.300",
        "description" => "cannot schedule(SD) unscheduled job"
    ],
    [
        "code" => "100.360.303",
        "description" => "cannot deschedule(DS) unscheduled job"
    ],
    [
        "code" => "100.360.400",
        "description" => "schedule module not configured for LIVE transaction mode"
    ],
    [
        "code" => "100.370.100",
        "description" => "transaction declined"
    ],
    [
        "code" => "100.370.101",
        "description" => "responseUrl not set in Transaction/Frontend"
    ],
    [
        "code" => "100.370.102",
        "description" => "malformed responseUrl in Transaction/Frontend"
    ],
    [
        "code" => "100.370.110",
        "description" => "transaction must be executed for German address"
    ],
    [
        "code" => "100.370.111",
        "description" => "system error( possible incorrect/missing input data)"
    ],
    [
        "code" => "100.370.121",
        "description" => "no or unknown ECI Type defined in Authentication"
    ],
    [
        "code" => "100.370.122",
        "description" => "parameter with null key provided in 3DSecure Authentication"
    ],
    [
        "code" => "100.370.123",
        "description" => "no or unknown verification type defined in 3DSecure Authentication"
    ],
    [
        "code" => "100.370.124",
        "description" => "unknown parameter key in 3DSecure Authentication"
    ],
    [
        "code" => "100.370.125",
        "description" => "Invalid 3DSecure Verification_ID. Must have Base64 encoding a Length of 28 digits"
    ],
    [
        "code" => "100.370.131",
        "description" => "no or unknown authentication type defined in Transaction/Authentication@type"
    ],
    [
        "code" => "100.370.132",
        "description" => "no result indicator defined Transaction/Authentication/resultIndicator"
    ],
    [
        "code" => "100.380.100",
        "description" => "transaction declined"
    ],
    [
        "code" => "100.380.101",
        "description" => "transaction contains no risk management part"
    ],
    [
        "code" => "100.380.110",
        "description" => "transaction must be executed for German address"
    ],
    [
        "code" => "100.380.201",
        "description" => "no risk management process type specified"
    ],
    [
        "code" => "100.380.305",
        "description" => "no frontend information provided for asynchronous transaction"
    ],
    [
        "code" => "100.380.306",
        "description" => "no authentication data provided in risk management transaction"
    ],
    [
        "code" => "100.380.401",
        "description" => "User Authentication Failed"
    ],
    [
        "code" => "100.380.501",
        "description" => "risk management transaction timeout"
    ],
    [
        "code" => "100.390.101",
        "description" => "purchase amount/currency mismatch"
    ],
    [
        "code" => "100.390.102",
        "description" => "PARes Validation failed"
    ],
    [
        "code" => "100.390.103",
        "description" => "PARes Validation failed - problem with signature"
    ],
    [
        "code" => "100.390.104",
        "description" => "XID mismatch"
    ],
    [
        "code" => "100.390.105",
        "description" => "Transaction rejected because of technical error in 3DSecure system"
    ],
    [
        "code" => "100.390.106",
        "description" => "Transaction rejected because of error in 3DSecure configuration"
    ],
    [
        "code" => "100.390.107",
        "description" => "Transaction rejected because cardholder authentication unavailable"
    ],
    [
        "code" => "100.390.108",
        "description" => "Transaction rejected because merchant not participating in 3DSecure program"
    ],
    [
        "code" => "100.390.109",
        "description" => "Transaction rejected because of VISA status 'U' or AMEX status 'N' or 'U' in 3DSecure program"
    ],
    [
        "code" => "100.390.110",
        "description" => "Cardholder Not Found - card number provided is not found in the ranges of the issuer"
    ],
    [
        "code" => "100.390.111",
        "description" => "Communication Error to VISA/Mastercard Directory Server"
    ],
    [
        "code" => "100.390.112",
        "description" => "Technical Error in 3D system"
    ],
    [
        "code" => "100.390.113",
        "description" => "Unsupported User Device - Authentication not possible"
    ],
    [
        "code" => "100.390.115",
        "description" => "Authentication failed due to invalid message format"
    ],
    [
        "code" => "100.390.116",
        "description" => "Access denied to the authentication system"
    ],
    [
        "code" => "100.390.117",
        "description" => "Authentication failed due to invalid data fields"
    ],
    [
        "code" => "100.395.101",
        "description" => "Bank not supported for Giropay"
    ],
    [
        "code" => "100.395.102",
        "description" => "Account not enabled for Giropay e.g. test account"
    ],
    [
        "code" => "100.395.501",
        "description" => "Previously pending online transfer transaction timed out"
    ],
    [
        "code" => "100.395.502",
        "description" => "Acquirer/Bank reported timeout on online transfer transaction"
    ],
    [
        "code" => "100.396.101",
        "description" => "Cancelled by user"
    ],
    [
        "code" => "100.396.102",
        "description" => "Not confirmed by user"
    ],
    [
        "code" => "100.396.103",
        "description" => "Previously pending transaction timed out"
    ],
    [
        "code" => "100.396.104",
        "description" => "Uncertain status - probably cancelled by user"
    ],
    [
        "code" => "100.396.106",
        "description" => "User did not agree to payment method terms"
    ],
    [
        "code" => "100.396.201",
        "description" => "Cancelled by merchant"
    ],
    [
        "code" => "100.397.101",
        "description" => "Cancelled by user due to external update"
    ],
    [
        "code" => "100.397.102",
        "description" => "Rejected by connector/acquirer due to external update"
    ],
    [
        "code" => "100.400.000",
        "description" => "transaction declined (Wrong Address)"
    ],
    [
        "code" => "100.400.001",
        "description" => "transaction declined (Wrong Identification)"
    ],
    [
        "code" => "100.400.002",
        "description" => "transaction declined (Insufficient credibility score)"
    ],
    [
        "code" => "100.400.005",
        "description" => "transaction must be executed for German address"
    ],
    [
        "code" => "100.400.007",
        "description" => "System error ( possible incorrect/missing input data)"
    ],
    [
        "code" => "100.400.020",
        "description" => "transaction declined"
    ],
    [
        "code" => "100.400.021",
        "description" => "transaction declined for country"
    ],
    [
        "code" => "100.400.030",
        "description" => "transaction not authorized. Please check manually"
    ],
    [
        "code" => "100.400.039",
        "description" => "transaction declined for other error"
    ],
    [
        "code" => "100.400.040",
        "description" => "authorization failure"
    ],
    [
        "code" => "100.400.041",
        "description" => "transaction must be executed for German address"
    ],
    [
        "code" => "100.400.042",
        "description" => "transaction declined by SCHUFA (Insufficient credibility score)"
    ],
    [
        "code" => "100.400.043",
        "description" => "transaction declined because of missing obligatory parameter(s)"
    ],
    [
        "code" => "100.400.044",
        "description" => "transaction not authorized. Please check manually"
    ],
    [
        "code" => "100.400.045",
        "description" => "SCHUFA result not definite. Please check manually"
    ],
    [
        "code" => "100.400.051",
        "description" => "SCHUFA system error (possible incorrect/missing input data)"
    ],
    [
        "code" => "100.400.060",
        "description" => "authorization failure"
    ],
    [
        "code" => "100.400.061",
        "description" => "transaction declined (Insufficient credibility score)"
    ],
    [
        "code" => "100.400.063",
        "description" => "transaction declined because of missing obligatory parameter(s)"
    ],
    [
        "code" => "100.400.064",
        "description" => "transaction must be executed for Austrian, German or Swiss address"
    ],
    [
        "code" => "100.400.065",
        "description" => "result ambiguous. Please check manually"
    ],
    [
        "code" => "100.400.071",
        "description" => "system error (possible incorrect/missing input data)"
    ],
    [
        "code" => "100.400.080",
        "description" => "authorization failure"
    ],
    [
        "code" => "100.400.081",
        "description" => "transaction declined"
    ],
    [
        "code" => "100.400.083",
        "description" => "transaction declined because of missing obligatory parameter(s)"
    ],
    [
        "code" => "100.400.084",
        "description" => "transaction can not be executed for given country"
    ],
    [
        "code" => "100.400.085",
        "description" => "result ambiguous. Please check manually"
    ],
    [
        "code" => "100.400.086",
        "description" => "transaction declined (Wrong Address)"
    ],
    [
        "code" => "100.400.087",
        "description" => "transaction declined (Wrong Identification)"
    ],
    [
        "code" => "100.400.091",
        "description" => "system error (possible incorrect/missing input data)"
    ],
    [
        "code" => "100.400.100",
        "description" => "transaction declined - very bad rating"
    ],
    [
        "code" => "100.400.120",
        "description" => "authorization failure"
    ],
    [
        "code" => "100.400.121",
        "description" => "account blacklisted"
    ],
    [
        "code" => "100.400.122",
        "description" => "transaction must be executed for valid German account"
    ],
    [
        "code" => "100.400.123",
        "description" => "transaction declined because of missing obligatory parameter(s)"
    ],
    [
        "code" => "100.400.130",
        "description" => "system error (possible incorrect/missing input data)"
    ],
    [
        "code" => "100.400.139",
        "description" => "system error (possible incorrect/missing input data)"
    ],
    [
        "code" => "100.400.140",
        "description" => "transaction declined by GateKeeper"
    ],
    [
        "code" => "100.400.141",
        "description" => "Challenge by ReD Shield"
    ],
    [
        "code" => "100.400.142",
        "description" => "Deny by ReD Shield"
    ],
    [
        "code" => "100.400.143",
        "description" => "Noscore by ReD Shield"
    ],
    [
        "code" => "100.400.144",
        "description" => "ReD Shield data error"
    ],
    [
        "code" => "100.400.145",
        "description" => "ReD Shield connection error"
    ],
    [
        "code" => "100.400.146",
        "description" => "Line item error by ReD Shield"
    ],
    [
        "code" => "100.400.147",
        "description" => "Payment void and transaction denied by ReD Shield"
    ],
    [
        "code" => "100.400.148",
        "description" => "Payment void and transaction challenged by ReD Shield"
    ],
    [
        "code" => "100.400.149",
        "description" => "Payment void and data error by ReD Shield"
    ],
    [
        "code" => "100.400.150",
        "description" => "Payment void and connection error by ReD Shield"
    ],
    [
        "code" => "100.400.151",
        "description" => "Payment void and line item error by ReD Shield"
    ],
    [
        "code" => "100.400.152",
        "description" => "Payment void and error returned by ReD Shield"
    ],
    [
        "code" => "100.400.241",
        "description" => "Challenged by Threat Metrix"
    ],
    [
        "code" => "100.400.242",
        "description" => "Denied by Threat Metrix"
    ],
    [
        "code" => "100.400.243",
        "description" => "Invalid sessionId"
    ],
    [
        "code" => "100.400.260",
        "description" => "authorization failure"
    ],
    [
        "code" => "100.400.300",
        "description" => "abort checkout process"
    ],
    [
        "code" => "100.400.301",
        "description" => "reenter age/birthdate"
    ],
    [
        "code" => "100.400.302",
        "description" => "reenter address (packstation not allowed)"
    ],
    [
        "code" => "100.400.303",
        "description" => "reenter address"
    ],
    [
        "code" => "100.400.304",
        "description" => "invalid input data"
    ],
    [
        "code" => "100.400.305",
        "description" => "invalid foreign address"
    ],
    [
        "code" => "100.400.306",
        "description" => "delivery address error"
    ],
    [
        "code" => "100.400.307",
        "description" => "offer only secure methods of payment"
    ],
    [
        "code" => "100.400.308",
        "description" => "offer only secure methods of payment; possibly abort checkout"
    ],
    [
        "code" => "100.400.309",
        "description" => "confirm corrected address; if not confirmed, offer secure methods of payment only"
    ],
    [
        "code" => "100.400.310",
        "description" => "confirm bank account data; if not confirmed, offer secure methods of payment only"
    ],
    [
        "code" => "100.400.311",
        "description" => "transaction declined (format error)"
    ],
    [
        "code" => "100.400.312",
        "description" => "transaction declined (invalid configuration data)"
    ],
    [
        "code" => "100.400.313",
        "description" => "currency field is invalid or missing"
    ],
    [
        "code" => "100.400.314",
        "description" => "amount invalid or empty"
    ],
    [
        "code" => "100.400.315",
        "description" => "invalid or missing email address (probably invalid syntax)"
    ],
    [
        "code" => "100.400.316",
        "description" => "transaction declined (card missing)"
    ],
    [
        "code" => "100.400.317",
        "description" => "transaction declined (invalid card)"
    ],
    [
        "code" => "100.400.318",
        "description" => "invalid IP number"
    ],
    [
        "code" => "100.400.319",
        "description" => "transaction declined by risk system"
    ],
    [
        "code" => "100.400.320",
        "description" => "shopping cart data invalid or missing"
    ],
    [
        "code" => "100.400.321",
        "description" => "payment type invalid or missing"
    ],
    [
        "code" => "100.400.322",
        "description" => "encryption method invalid or missing"
    ],
    [
        "code" => "100.400.323",
        "description" => "certificate invalid or missing"
    ],
    [
        "code" => "100.400.324",
        "description" => "Error on the external risk system"
    ],
    [
        "code" => "100.400.325",
        "description" => "External risk system not available"
    ],
    [
        "code" => "100.400.326",
        "description" => "Risk bank account check unsuccessful"
    ],
    [
        "code" => "100.400.327",
        "description" => "Risk report unsuccessful"
    ],
    [
        "code" => "100.400.328",
        "description" => "Risk report unsuccessful (invalid data)"
    ],
    [
        "code" => "100.400.500",
        "description" => "waiting for external risk"
    ],
    [
        "code" => "100.500.101",
        "description" => "payment method invalid"
    ],
    [
        "code" => "100.500.201",
        "description" => "payment type invalid"
    ],
    [
        "code" => "100.500.301",
        "description" => "invalid due date"
    ],
    [
        "code" => "100.500.302",
        "description" => "invalid mandate date of signature"
    ],
    [
        "code" => "100.500.303",
        "description" => "invalid mandate id"
    ],
    [
        "code" => "100.500.304",
        "description" => "invalid mandate external id"
    ],
    [
        "code" => "100.550.300",
        "description" => "request contains no amount or too low amount"
    ],
    [
        "code" => "100.550.301",
        "description" => "amount too large"
    ],
    [
        "code" => "100.550.303",
        "description" => "amount format invalid (only two decimals allowed)."
    ],
    [
        "code" => "100.550.310",
        "description" => "amount exceeds limit for the registered account."
    ],
    [
        "code" => "100.550.311",
        "description" => "exceeding account balance"
    ],
    [
        "code" => "100.550.312",
        "description" => "Amount is outside allowed ticket size boundaries"
    ],
    [
        "code" => "100.550.400",
        "description" => "request contains no currency"
    ],
    [
        "code" => "100.550.401",
        "description" => "invalid currency"
    ],
    [
        "code" => "100.550.601",
        "description" => "risk amount too large"
    ],
    [
        "code" => "100.550.603",
        "description" => "risk amount format invalid (only two decimals allowed)"
    ],
    [
        "code" => "100.550.605",
        "description" => "risk amount is smaller than amount (it must be equal or bigger then amount)"
    ],
    [
        "code" => "100.550.701",
        "description" => "amounts not matched"
    ],
    [
        "code" => "100.550.702",
        "description" => "currencies not matched"
    ],
    [
        "code" => "100.600.500",
        "description" => "usage field too long"
    ],
    [
        "code" => "100.700.100",
        "description" => "customer.surname may not be null"
    ],
    [
        "code" => "100.700.101",
        "description" => "customer.surname length must be between 0 and 50"
    ],
    [
        "code" => "100.700.200",
        "description" => "customer.givenName may not be null"
    ],
    [
        "code" => "100.700.201",
        "description" => "customer.givenName length must be between 0 and 50"
    ],
    [
        "code" => "100.700.300",
        "description" => "invalid salutation"
    ],
    [
        "code" => "100.700.400",
        "description" => "invalid title"
    ],
    [
        "code" => "100.700.500",
        "description" => "company name too long"
    ],
    [
        "code" => "100.700.800",
        "description" => "identity contains no or invalid 'paper'"
    ],
    [
        "code" => "100.700.801",
        "description" => "identity contains no or invalid identification value"
    ],
    [
        "code" => "100.700.802",
        "description" => "identification value too long"
    ],
    [
        "code" => "100.700.810",
        "description" => "specify at least one identity"
    ],
    [
        "code" => "100.800.100",
        "description" => "request contains no street"
    ],
    [
        "code" => "100.800.101",
        "description" => "The combination of street1 and street2 must not exceed 201 characters."
    ],
    [
        "code" => "100.800.102",
        "description" => "The combination of street1 and street2 must not contain only numbers."
    ],
    [
        "code" => "100.800.200",
        "description" => "request contains no zip"
    ],
    [
        "code" => "100.800.201",
        "description" => "zip too long"
    ],
    [
        "code" => "100.800.202",
        "description" => "invalid zip"
    ],
    [
        "code" => "100.800.300",
        "description" => "request contains no city"
    ],
    [
        "code" => "100.800.301",
        "description" => "city too long"
    ],
    [
        "code" => "100.800.302",
        "description" => "invalid city"
    ],
    [
        "code" => "100.800.400",
        "description" => "invalid state/country combination"
    ],
    [
        "code" => "100.800.401",
        "description" => "state too long"
    ],
    [
        "code" => "100.800.500",
        "description" => "request contains no country"
    ],
    [
        "code" => "100.800.501",
        "description" => "invalid country"
    ],
    [
        "code" => "100.900.100",
        "description" => "request contains no email address"
    ],
    [
        "code" => "100.900.101",
        "description" => "invalid email address (probably invalid syntax)"
    ],
    [
        "code" => "100.900.105",
        "description" => "email address too long (max 50 chars)"
    ],
    [
        "code" => "100.900.200",
        "description" => "invalid phone number (has to start with a digit or a '+', at least 7 and max 25 chars long)"
    ],
    [
        "code" => "100.900.300",
        "description" => "invalid mobile phone number (has to start with a digit or a '+', at least 7 and max 25 chars long)"
    ],
    [
        "code" => "100.900.301",
        "description" => "mobile phone number mandatory"
    ],
    [
        "code" => "100.900.400",
        "description" => "request contains no ip number"
    ],
    [
        "code" => "100.900.401",
        "description" => "invalid ip number"
    ],
    [
        "code" => "100.900.450",
        "description" => "invalid birthdate"
    ],
    [
        "code" => "100.900.500",
        "description" => "invalid recurrence mode"
    ],
    [
        "code" => "200.100.101",
        "description" => "invalid Request Message. No valid XML. XML must be url-encoded! maybe it contains a not encoded ampersand or something similar."
    ],
    [
        "code" => "200.100.102",
        "description" => "invalid Request. XML load missing (XML string must be sent within parameter 'load')"
    ],
    [
        "code" => "200.100.103",
        "description" => "invalid Request Message. The request contains structural errors"
    ],
    [
        "code" => "200.100.150",
        "description" => "transaction of multirequest not processed because of subsequent problems"
    ],
    [
        "code" => "200.100.151",
        "description" => "multi-request is allowed with a maximum of 10 transactions only"
    ],
    [
        "code" => "200.100.199",
        "description" => "Wrong Web Interface / URL used. Please check out the Tech Quick Start Doc Chapter 3."
    ],
    [
        "code" => "200.100.201",
        "description" => "invalid Request/Transaction tag (not present or [partially] empty)"
    ],
    [
        "code" => "200.100.300",
        "description" => "invalid Request/Transaction/Payment tag (no or invalid code specified)"
    ],
    [
        "code" => "200.100.301",
        "description" => "invalid Request/Transaction/Payment tag (not present or [partially] empty)"
    ],
    [
        "code" => "200.100.302",
        "description" => "invalid Request/Transaction/Payment/Presentation tag (not present or [partially] empty)"
    ],
    [
        "code" => "200.100.401",
        "description" => "invalid Request/Transaction/Account tag (not present or [partially] empty)"
    ],
    [
        "code" => "200.100.402",
        "description" => "invalid Request/Transaction/Account(Customer, Relevance) tag (one of Account/Customer/Relevance must be present)"
    ],
    [
        "code" => "200.100.403",
        "description" => "invalid Request/Transaction/Analysis tag (Criterions must have a name and value)"
    ],
    [
        "code" => "200.100.404",
        "description" => "invalid Request/Transaction/Account (must not be present)"
    ],
    [
        "code" => "200.100.501",
        "description" => "invalid or missing customer"
    ],
    [
        "code" => "200.100.502",
        "description" => "invalid Request/Transaction/Customer/Name tag (not present or [partially] empty)"
    ],
    [
        "code" => "200.100.503",
        "description" => "invalid Request/Transaction/Customer/Contact tag (not present or [partially] empty)"
    ],
    [
        "code" => "200.100.504",
        "description" => "invalid Request/Transaction/Customer/Address tag (not present or [partially] empty)"
    ],
    [
        "code" => "200.100.601",
        "description" => "invalid Request/Transaction/(ApplePay|GooglePay) tag (not present or [partially] empty)"
    ],
    [
        "code" => "200.100.602",
        "description" => "invalid Request/Transaction/(ApplePay|GooglePay)/PaymentToken tag (not present or [partially] empty)"
    ],
    [
        "code" => "200.100.603",
        "description" => "invalid Request/Transaction/(ApplePay|GooglePay)/PaymentToken tag (decryption error)"
    ],
    [
        "code" => "200.200.106",
        "description" => "duplicate transaction. Please verify that the UUID is unique"
    ],
    [
        "code" => "200.300.403",
        "description" => "Invalid HTTP method"
    ],
    [
        "code" => "200.300.404",
        "description" => "البيانات غير صحيحة / غير مدفوع"
    ],
    [
        "code" => "200.300.405",
        "description" => "Duplicate entity"
    ],
    [
        "code" => "200.300.406",
        "description" => "Entity not found"
    ],
    [
        "code" => "200.300.407",
        "description" => "Entity not specific enough"
    ],
    [
        "code" => "300.100.100",
        "description" => "Transaction declined (additional customer authentication required)"
    ],
    [
        "code" => "500.100.201",
        "description" => "Channel/Merchant is disabled (no processing possible)"
    ],
    [
        "code" => "500.100.202",
        "description" => "Channel/Merchant is new (no processing possible yet)"
    ],
    [
        "code" => "500.100.203",
        "description" => "Channel/Merchant is closed (no processing possible)"
    ],
    [
        "code" => "500.100.301",
        "description" => "Merchant-Connector is disabled (no processing possible)"
    ],
    [
        "code" => "500.100.302",
        "description" => "Merchant-Connector is new (no processing possible yet)"
    ],
    [
        "code" => "500.100.303",
        "description" => "Merchant-Connector is closed (no processing possible)"
    ],
    [
        "code" => "500.100.304",
        "description" => "Merchant-Connector is disabled at gateway (no processing possible)"
    ],
    [
        "code" => "500.100.401",
        "description" => "Connector is unavailable (no processing possible)"
    ],
    [
        "code" => "500.100.402",
        "description" => "Connector is new (no processing possible yet)"
    ],
    [
        "code" => "500.100.403",
        "description" => "Connector is unavailable (no processing possible)"
    ],
    [
        "code" => "500.200.101",
        "description" => "No target account configured for DD transaction"
    ],
    [
        "code" => "600.100.100",
        "description" => "Unexpected Integrator Error (Request could not be processed)"
    ],
    [
        "code" => "600.200.100",
        "description" => "invalid Payment Method"
    ],
    [
        "code" => "600.200.200",
        "description" => "Unsupported Payment Method"
    ],
    [
        "code" => "600.200.201",
        "description" => "Channel/Merchant not configured for this payment method"
    ],
    [
        "code" => "600.200.202",
        "description" => "Channel/Merchant not configured for this payment type"
    ],
    [
        "code" => "600.200.300",
        "description" => "invalid Payment Type"
    ],
    [
        "code" => "600.200.310",
        "description" => "invalid Payment Type for given Payment Method"
    ],
    [
        "code" => "600.200.400",
        "description" => "Unsupported Payment Type"
    ],
    [
        "code" => "600.200.500",
        "description" => "Invalid payment data. You are not configured for this currency or sub type (country or brand)"
    ],
    [
        "code" => "600.200.501",
        "description" => "Invalid payment data for Recurring transaction. Merchant or transaction data has wrong recurring configuration."
    ],
    [
        "code" => "600.200.600",
        "description" => "invalid payment code (type or method)"
    ],
    [
        "code" => "600.200.700",
        "description" => "invalid payment mode (you are not configured for the requested transaction mode)"
    ],
    [
        "code" => "600.200.800",
        "description" => "invalid brand for given payment method and payment mode (you are not configured for the requested transaction mode)"
    ],
    [
        "code" => "600.200.810",
        "description" => "invalid return code provided"
    ],
    [
        "code" => "600.300.101",
        "description" => "Merchant key not found"
    ],
    [
        "code" => "600.300.200",
        "description" => "merchant source IP address not whitelisted"
    ],
    [
        "code" => "600.300.210",
        "description" => "merchant notificationUrl not whitelisted"
    ],
    [
        "code" => "600.300.211",
        "description" => "shopperResultUrl not whitelisted"
    ],
    [
        "code" => "700.100.100",
        "description" => "reference id not existing"
    ],
    [
        "code" => "700.100.200",
        "description" => "non matching reference amount"
    ],
    [
        "code" => "700.100.300",
        "description" => "invalid amount (probably too large)"
    ],
    [
        "code" => "700.100.400",
        "description" => "referenced payment method does not match with requested payment method"
    ],
    [
        "code" => "700.100.500",
        "description" => "referenced payment currency does not match with requested payment currency"
    ],
    [
        "code" => "700.100.600",
        "description" => "referenced mode does not match with requested payment mode"
    ],
    [
        "code" => "700.100.700",
        "description" => "referenced transaction is of inappropriate type"
    ],
    [
        "code" => "700.100.701",
        "description" => "referenced a DB transaction without explicitly providing an account. Not allowed to used referenced account."
    ],
    [
        "code" => "700.100.710",
        "description" => "cross-linkage of two transaction-trees"
    ],
    [
        "code" => "700.300.100",
        "description" => "referenced tx can not be refunded, captured or reversed (invalid type)"
    ],
    [
        "code" => "700.300.200",
        "description" => "referenced tx was rejected"
    ],
    [
        "code" => "700.300.300",
        "description" => "referenced tx can not be refunded, captured or reversed (already refunded, captured or reversed)"
    ],
    [
        "code" => "700.300.400",
        "description" => "referenced tx can not be captured (cut off time reached)"
    ],
    [
        "code" => "700.300.500",
        "description" => "chargeback error (multiple chargebacks)"
    ],
    [
        "code" => "700.300.600",
        "description" => "referenced tx can not be refunded or reversed (was chargebacked)"
    ],
    [
        "code" => "700.300.700",
        "description" => "referenced tx can not be reversed (reversal not possible anymore)"
    ],
    [
        "code" => "700.300.800",
        "description" => "referenced tx can not be voided"
    ],
    [
        "code" => "700.400.000",
        "description" => "serious workflow error (call support)"
    ],
    [
        "code" => "700.400.100",
        "description" => "cannot capture (PA value exceeded, PA reverted or invalid workflow?)"
    ],
    [
        "code" => "700.400.101",
        "description" => "cannot capture (Not supported by authorization system)"
    ],
    [
        "code" => "700.400.200",
        "description" => "cannot refund (refund volume exceeded or tx reversed or invalid workflow?)"
    ],
    [
        "code" => "700.400.300",
        "description" => "cannot reverse (already refunded|reversed, invalid workflow or amount exceeded)"
    ],
    [
        "code" => "700.400.400",
        "description" => "cannot chargeback (already chargebacked or invalid workflow?)"
    ],
    [
        "code" => "700.400.402",
        "description" => "chargeback can only be generated internally by the payment system"
    ],
    [
        "code" => "700.400.410",
        "description" => "cannot reversal chargeback (chargeback is already reversaled or invalid workflow?)"
    ],
    [
        "code" => "700.400.420",
        "description" => "cannot reversal chargeback (no chargeback existing or invalid workflow?)"
    ],
    [
        "code" => "700.400.510",
        "description" => "capture needs at least one successful transaction of type (PA)"
    ],
    [
        "code" => "700.400.520",
        "description" => "refund needs at least one successful transaction of type (CP or DB or RB or RC)"
    ],
    [
        "code" => "700.400.530",
        "description" => "reversal needs at least one successful transaction of type (CP or DB or RB or PA)"
    ],
    [
        "code" => "700.400.540",
        "description" => "reconceile needs at least one successful transaction of type (CP or DB or RB)"
    ],
    [
        "code" => "700.400.550",
        "description" => "chargeback needs at least one successful transaction of type (CP or DB or RB)"
    ],
    [
        "code" => "700.400.560",
        "description" => "receipt needs at least one successful transaction of type (PA or CP or DB or RB)"
    ],
    [
        "code" => "700.400.561",
        "description" => "receipt on a registration needs a successfull registration in state 'OPEN'"
    ],
    [
        "code" => "700.400.562",
        "description" => "receipts can only be generated internally by the payment system"
    ],
    [
        "code" => "700.400.565",
        "description" => "finalize needs at least one successful transaction of type (PA or DB)"
    ],
    [
        "code" => "700.400.570",
        "description" => "cannot reference a waiting/pending transaction"
    ],
    [
        "code" => "700.400.580",
        "description" => "cannot find transaction"
    ],
    [
        "code" => "700.400.700",
        "description" => "initial and referencing channel-ids do not match"
    ],
    [
        "code" => "700.450.001",
        "description" => "cannot transfer money from one account to the same account"
    ],
    [
        "code" => "700.500.001",
        "description" => "referenced session contains too many transactions"
    ],
    [
        "code" => "700.500.002",
        "description" => "capture or preauthorization appears too late in referenced session"
    ],
    [
        "code" => "700.500.003",
        "description" => "test accounts not allowed in production"
    ],
    [
        "code" => "700.500.004",
        "description" => "cannot refer a transaction which contains deleted customer information"
    ],
    [
        "code" => "800.100.100",
        "description" => "transaction declined for unknown reason"
    ],
    [
        "code" => "800.100.150",
        "description" => "transaction declined (refund on gambling tx not allowed)"
    ],
    [
        "code" => "800.100.151",
        "description" => "transaction declined (invalid card)"
    ],
    [
        "code" => "800.100.152",
        "description" => "transaction declined by authorization system"
    ],
    [
        "code" => "800.100.153",
        "description" => "transaction declined (invalid CVV)"
    ],
    [
        "code" => "800.100.154",
        "description" => "transaction declined (transaction marked as invalid)"
    ],
    [
        "code" => "800.100.155",
        "description" => "transaction declined (amount exceeds credit)"
    ],
    [
        "code" => "800.100.156",
        "description" => "transaction declined (format error)"
    ],
    [
        "code" => "800.100.157",
        "description" => "transaction declined (wrong expiry date)"
    ],
    [
        "code" => "800.100.158",
        "description" => "transaction declined (suspecting manipulation)"
    ],
    [
        "code" => "800.100.159",
        "description" => "transaction declined (stolen card)"
    ],
    [
        "code" => "800.100.160",
        "description" => "transaction declined (card blocked)"
    ],
    [
        "code" => "800.100.161",
        "description" => "transaction declined (too many invalid tries)"
    ],
    [
        "code" => "800.100.162",
        "description" => "transaction declined (limit exceeded)"
    ],
    [
        "code" => "800.100.163",
        "description" => "transaction declined (maximum transaction frequency exceeded)"
    ],
    [
        "code" => "800.100.164",
        "description" => "transaction declined (merchants limit exceeded)"
    ],
    [
        "code" => "800.100.165",
        "description" => "transaction declined (card lost)"
    ],
    [
        "code" => "800.100.166",
        "description" => "transaction declined (Incorrect personal identification number)"
    ],
    [
        "code" => "800.100.167",
        "description" => "transaction declined (referencing transaction does not match)"
    ],
    [
        "code" => "800.100.168",
        "description" => "transaction declined (restricted card)"
    ],
    [
        "code" => "800.100.169",
        "description" => "transaction declined (card type is not processed by the authorization center)"
    ],
    [
        "code" => "800.100.170",
        "description" => "transaction declined (transaction not permitted)"
    ],
    [
        "code" => "800.100.171",
        "description" => "transaction declined (pick up card)"
    ],
    [
        "code" => "800.100.172",
        "description" => "transaction declined (account blocked)"
    ],
    [
        "code" => "800.100.173",
        "description" => "transaction declined (invalid currency, not processed by authorization center)"
    ],
    [
        "code" => "800.100.174",
        "description" => "transaction declined (invalid amount)"
    ],
    [
        "code" => "800.100.175",
        "description" => "transaction declined (invalid brand)"
    ],
    [
        "code" => "800.100.176",
        "description" => "transaction declined (account temporarily not available. Please try again later)"
    ],
    [
        "code" => "800.100.177",
        "description" => "transaction declined (amount field should not be empty)"
    ],
    [
        "code" => "800.100.178",
        "description" => "transaction declined (PIN entered incorrectly too often)"
    ],
    [
        "code" => "800.100.179",
        "description" => "transaction declined (exceeds withdrawal count limit)"
    ],
    [
        "code" => "800.100.190",
        "description" => "transaction declined (invalid configuration data)"
    ],
    [
        "code" => "800.100.191",
        "description" => "transaction declined (transaction in wrong state on aquirer side)"
    ],
    [
        "code" => "800.100.192",
        "description" => "transaction declined (invalid CVV, Amount has still been reserved on the customer's card and will be released in a few business days. Please ensure the CVV code is accurate before retrying the transaction)"
    ],
    [
        "code" => "800.100.195",
        "description" => "transaction declined (UserAccount Number/ID unknown)"
    ],
    [
        "code" => "800.100.196",
        "description" => "transaction declined (registration error)"
    ],
    [
        "code" => "800.100.197",
        "description" => "transaction declined (registration cancelled externally)"
    ],
    [
        "code" => "800.100.198",
        "description" => "transaction declined (invalid holder)"
    ],
    [
        "code" => "800.100.199",
        "description" => "transaction declined (invalid tax number)"
    ],
    [
        "code" => "800.100.402",
        "description" => "cc/bank account holder not valid"
    ],
    [
        "code" => "800.100.403",
        "description" => "transaction declined (revocation of authorisation order)"
    ],
    [
        "code" => "800.100.500",
        "description" => "Card holder has advised his bank to stop this recurring payment"
    ],
    [
        "code" => "800.100.501",
        "description" => "Card holder has advised his bank to stop all recurring payments for this merchant"
    ],
    [
        "code" => "800.110.100",
        "description" => "duplicate transaction"
    ],
    [
        "code" => "800.120.100",
        "description" => "Rejected by Throttling."
    ],
    [
        "code" => "800.120.101",
        "description" => "maximum number of transactions per account already exceeded"
    ],
    [
        "code" => "800.120.102",
        "description" => "maximum number of transactions per ip already exceeded"
    ],
    [
        "code" => "800.120.103",
        "description" => "maximum number of transactions per email already exceeded"
    ],
    [
        "code" => "800.120.200",
        "description" => "maximum total volume of transactions already exceeded"
    ],
    [
        "code" => "800.120.201",
        "description" => "maximum total volume of transactions per account already exceeded"
    ],
    [
        "code" => "800.120.202",
        "description" => "maximum total volume of transactions per ip already exceeded"
    ],
    [
        "code" => "800.120.203",
        "description" => "maximum total volume of transactions per email already exceeded"
    ],
    [
        "code" => "800.120.300",
        "description" => "chargeback rate per bin exceeded"
    ],
    [
        "code" => "800.120.401",
        "description" => "maximum number of transactions or total volume for configured MIDs or CIs exceeded"
    ],
    [
        "code" => "800.121.100",
        "description" => "Channel not configured for given source type. Please contact your account manager."
    ],
    [
        "code" => "800.121.200",
        "description" => "Secure Query is not enabled for this entity. Please contact your account manager."
    ],
    [
        "code" => "800.130.100",
        "description" => "Transaction with same TransactionId already exists"
    ],
    [
        "code" => "800.140.100",
        "description" => "maximum number of registrations per mobile number exceeded"
    ],
    [
        "code" => "800.140.101",
        "description" => "maximum number of registrations per email address exceeded"
    ],
    [
        "code" => "800.140.110",
        "description" => "maximum number of registrations of mobile per credit card number exceeded"
    ],
    [
        "code" => "800.140.111",
        "description" => "maximum number of registrations of credit card number per mobile exceeded"
    ],
    [
        "code" => "800.140.112",
        "description" => "maximum number of registrations of email per credit card number exceeded"
    ],
    [
        "code" => "800.140.113",
        "description" => "maximum number of registrations of credit card number per email exceeded"
    ],
    [
        "code" => "800.150.100",
        "description" => "Account Holder does not match Customer Name"
    ],
    [
        "code" => "800.160.100",
        "description" => "Invalid payment data for configured Shopper Dispatching Type"
    ],
    [
        "code" => "800.160.110",
        "description" => "Invalid payment data for configured Payment Dispatching Type"
    ],
    [
        "code" => "800.160.120",
        "description" => "Invalid payment data for configured Recurring Transaction Dispatching Type"
    ],
    [
        "code" => "800.160.130",
        "description" => "Invalid payment data for configured TicketSize Dispatching Type"
    ],
    [
        "code" => "800.200.159",
        "description" => "account or user is blacklisted (card stolen)"
    ],
    [
        "code" => "800.200.160",
        "description" => "account or user is blacklisted (card blocked)"
    ],
    [
        "code" => "800.200.165",
        "description" => "account or user is blacklisted (card lost)"
    ],
    [
        "code" => "800.200.202",
        "description" => "account or user is blacklisted (account closed)"
    ],
    [
        "code" => "800.200.208",
        "description" => "account or user is blacklisted (account blocked)"
    ],
    [
        "code" => "800.200.220",
        "description" => "account or user is blacklisted (fraudulent transaction)"
    ],
    [
        "code" => "800.300.101",
        "description" => "account or user is blacklisted"
    ],
    [
        "code" => "800.300.102",
        "description" => "country blacklisted"
    ],
    [
        "code" => "800.300.200",
        "description" => "email is blacklisted"
    ],
    [
        "code" => "800.300.301",
        "description" => "ip blacklisted"
    ],
    [
        "code" => "800.300.302",
        "description" => "ip is anonymous proxy"
    ],
    [
        "code" => "800.300.401",
        "description" => "bin blacklisted"
    ],
    [
        "code" => "800.300.500",
        "description" => "transaction temporary blacklisted (too many tries invalid CVV)"
    ],
    [
        "code" => "800.300.501",
        "description" => "transaction temporary blacklisted (too many tries invalid expire date)"
    ],
    [
        "code" => "800.310.200",
        "description" => "Account closed"
    ],
    [
        "code" => "800.310.210",
        "description" => "Account not found"
    ],
    [
        "code" => "800.310.211",
        "description" => "Account not found (BIN/issuer not participating)"
    ],
    [
        "code" => "800.400.100",
        "description" => "AVS Check Failed"
    ],
    [
        "code" => "800.400.101",
        "description" => "Mismatch of AVS street value"
    ],
    [
        "code" => "800.400.102",
        "description" => "Mismatch of AVS street number"
    ],
    [
        "code" => "800.400.103",
        "description" => "Mismatch of AVS PO box value fatal"
    ],
    [
        "code" => "800.400.104",
        "description" => "Mismatch of AVS zip code value fatal"
    ],
    [
        "code" => "800.400.105",
        "description" => "Mismatch of AVS settings (AVSkip, AVIgnore, AVSRejectPolicy) value"
    ],
    [
        "code" => "800.400.110",
        "description" => "AVS Check Failed. Amount has still been reserved on the customer's card and will be released in a few business days. Please ensure the billing address is accurate before retrying the transaction."
    ],
    [
        "code" => "800.400.150",
        "description" => "Implausible address data"
    ],
    [
        "code" => "800.400.151",
        "description" => "Implausible address state data"
    ],
    [
        "code" => "800.400.200",
        "description" => "Invalid Payer Authentication in 3DSecure transaction"
    ],
    [
        "code" => "800.400.500",
        "description" => "Waiting for confirmation of non-instant payment. Denied for now."
    ],
    [
        "code" => "800.400.501",
        "description" => "Waiting for confirmation of non-instant debit. Denied for now."
    ],
    [
        "code" => "800.400.502",
        "description" => "Waiting for confirmation of non-instant refund. Denied for now."
    ],
    [
        "code" => "800.500.100",
        "description" => "direct debit transaction declined for unknown reason"
    ],
    [
        "code" => "800.500.110",
        "description" => "Unable to process transaction - ran out of terminalIds - please contact acquirer"
    ],
    [
        "code" => "800.600.100",
        "description" => "transaction is being already processed"
    ],
    [
        "code" => "800.700.100",
        "description" => "transaction for the same session is currently being processed, please try again later."
    ],
    [
        "code" => "800.700.101",
        "description" => "family name too long"
    ],
    [
        "code" => "800.700.201",
        "description" => "given name too long"
    ],
    [
        "code" => "800.700.500",
        "description" => "company name too long"
    ],
    [
        "code" => "800.800.102",
        "description" => "Invalid street"
    ],
    [
        "code" => "800.800.202",
        "description" => "Invalid zip"
    ],
    [
        "code" => "800.800.302",
        "description" => "Invalid city"
    ],
    [
        "code" => "800.800.400",
        "description" => "Connector/acquirer system is under maintenance"
    ],
    [
        "code" => "800.800.800",
        "description" => "The payment system is currenty unavailable, please contact support in case this happens again."
    ],
    [
        "code" => "800.800.801",
        "description" => "The payment system is currenty unter maintenance. Please apologize for the inconvenience this may cause. If you were not informed of this maintenance window in advance, contact your sales representative."
    ],
    [
        "code" => "800.900.100",
        "description" => "sender authorization failed "
    ],
    [
        "code" => "800.900.101",
        "description" => "invalid email address (probably invalid syntax)"
    ],
    [
        "code" => "800.900.200",
        "description" => "invalid phone number (has to start with a digit or a '+', at least 7 and max 25 chars long)"
    ],
    [
        "code" => "800.900.201",
        "description" => "unknown channel"
    ],
    [
        "code" => "800.900.300",
        "description" => "invalid authentication information"
    ],
    [
        "code" => "800.900.301",
        "description" => "user authorization failed, user has no sufficient rights to process transaction"
    ],
    [
        "code" => "800.900.302",
        "description" => "Authorization failed"
    ],
    [
        "code" => "800.900.303",
        "description" => "No token created"
    ],
    [
        "code" => "800.900.399",
        "description" => "Secure Registration Problem"
    ],
    [
        "code" => "800.900.401",
        "description" => "Invalid IP number"
    ],
    [
        "code" => "800.900.450",
        "description" => "Invalid birthdate"
    ],
    [
        "code" => "900.100.100",
        "description" => "unexpected communication error with connector/acquirer"
    ],
    [
        "code" => "900.100.200",
        "description" => "error response from connector/acquirer"
    ],
    [
        "code" => "900.100.201",
        "description" => "error on the external gateway (e.g. on the part of the bank, acquirer,...)"
    ],
    [
        "code" => "900.100.202",
        "description" => "invalid transaction flow, the requested function is not applicable for the referenced transaction."
    ],
    [
        "code" => "900.100.203",
        "description" => "error on the internal gateway"
    ],
    [
        "code" => "900.100.300",
        "description" => "timeout, uncertain result"
    ],
    [
        "code" => "900.100.301",
        "description" => "Transaction timed out without response from connector/acquirer. It was reversed."
    ],
    [
        "code" => "900.100.310",
        "description" => "Transaction timed out due to internal system misconfiguration. Request to acquirer has not been sent."
    ],
    [
        "code" => "900.100.400",
        "description" => "timeout at connectors/acquirer side"
    ],
    [
        "code" => "900.100.500",
        "description" => "timeout at connectors/acquirer side (try later)"
    ],
    [
        "code" => "900.100.600",
        "description" => "connector/acquirer currently down"
    ],
    [
        "code" => "900.200.100",
        "description" => "Message Sequence Number of Connector out of sync"
    ],
    [
        "code" => "900.300.600",
        "description" => "user session timeout"
    ],
    [
        "code" => "900.400.100",
        "description" => "unexpected communication error with external risk provider"
    ],
    [
        "code" => "999.999.888",
        "description" => "UNDEFINED PLATFORM DATABASE ERROR"
    ],
    [
        "code" => "999.999.999",
        "description" => "UNDEFINED CONNECTOR/ACQUIRER ERROR"
    ],
    [
        "code" => "1",
        "description" => "الدفع عند التوصيل"
    ]
];
