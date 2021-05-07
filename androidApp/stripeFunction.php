<?php
function createStripeAccount($firstName,$lastName,$phone,$email,$address,$zip,$city,$birthdate,$accountNb){

  $token = $stripe->tokens->create([
    'account' => [
      'business_type' => 'individual',
      'individual' => [
        'first_name' => $firstName,
        'last_name' => $lastName,
        'phone' => '+33 '.$phone,
        'email' => $email,
        'dob' => [
          'day' => '18',
          'month' => '05',
          'year' => '2000',
        ],
        'address' => [
          'country' => 'FR',
          'city' => $city,
          'postal_code' => $zip,
          'line1' => $address,
        ],
        'verification' => [
          'document' => [
            'front' => 'file_identity_document_success',
          ],
          'additional_document' => [
            'front' => 'file_identity_document_success',
          ],
        ],
      ],
      'tos_shown_and_accepted' => true,
    ],
  ]);

  $account = $stripe->accounts->create([
    'country' => 'FR',
    'type' => 'custom',
    'capabilities' => [
      'card_payments' => [
        'requested' => true,
      ],
      'transfers' => [
        'requested' => true,
      ],
    ],
    'business_profile' => [
      'mcc' => '4789',
      'url' => 'https://pa2021-esgi.herokuapp.com/',
    ],
    'settings' => [
      'payouts' => [
        'schedule' => [
          'interval' => 'manual',
        ],
      ],
    ],
    'account_token' => $token->id,
  ]);

  $bank = $stripe->tokens->create([
    'bank_account' => [
      'country' => 'FR',
      'currency' => 'eur',
      'account_holder_name' => $firstName.' '.$lastName,
      'account_holder_type' => 'individual',
      'account_number' => $accountNb,
    ],
  ]);

  $stripe->accounts->createExternalAccount(
    $account->id,
    [
      'external_account' => $bank->id,
    ]
  );

  return $account->id;
}

function updateStripeAccount($stripe,$stripeId,$accountId){
  $token= $stripe->tokens->create([
    'account' => ['tos_shown_and_accepted' => true,],
  ]);

  $stripe->accounts->update(
    $stripeId,
    [ 'account_token' => $token->id,
      'external_account' =>
      [
        'object' => 'bank_account',
        'country' => 'FR',
        'currency' => 'eur',
        'account_number' => $accountId,
      ]
    ]
  );

}
 ?>
