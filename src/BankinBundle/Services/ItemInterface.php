<?php

namespace BankinBundle\Services;

/**
 *
 */
interface ItemInterface
{
    const ITEM_STATUS_OK = 0; // Everything is awesome.
    const ITEM_STATUS_JUST_ADDED = -2; // The account was recently added. The account refresh is pending.
    const ITEM_STATUS_JUST_EDITED = -3; // The account's credentials were recently changed. The account refresh is pending.
    const ITEM_STATUS_LOGIN_FAILED = 402; // Wrong credentials.
    const ITEM_STATUS_NEED_HUMAN_ACTION = 429; // An action from the user is required within the online banking of the user.
    const ITEM_STATUS_NEED_PASSWORD_ROTATION = 430; // The bank is asking the user to change his password.
    const ITEM_STATUS_COULD_NOT_REFRESH = 1003; // Couldn't refresh. Try again.
    const ITEM_STATUS_NOT_SUPPORTED = 1005; // Bank not supported (ex: wrong/unsupported region)
    const ITEM_STATUS_DISABLED_TEMPORARILY = 1007; // Refresh temporarily disabled.
    const ITEM_STATUS_INCOMPLETE = 1009; // Account balance has changed but no new transactions were found.
    const ITEM_STATUS_NEEDS_MANUAL_REFRESH = 1010; // Item wasn't refreshed successfully, it required an MFA / OTP that wasn't provided.
    const ITEM_STATUS_MIGRATION = 1099; // Item is migrating to another bank.
    
    
    const ITEM_REFRESH_STATUS_AUTHENTICATING = 'authenticating'; // The item is connecting to the bank.
    const ITEM_REFRESH_STATUS_RETRIEVING_DATA = 'retrieving_data'; // 'refreshed_accounts_count' and 'total_accounts_count' will indicate the progression of the refresh.
    const ITEM_REFRESH_STATUS_INFO_REQUIRED = 'info_required'; 
    const ITEM_REFRESH_STATUS_FINISHED = 'finished';  // Refresh process is done. All data are up to date and available.
    const ITEM_REFRESH_STATUS_INVALID_CREDS = 'invalid_creds'; // Provided credentials are invalid. Corresponding's item status is 402.
    const ITEM_REFRESH_STATUS_FINISHED_ERROR = 'finished_error'; // An error occurred while refreshing the item. Check its status (429, 1003, ...).
}
