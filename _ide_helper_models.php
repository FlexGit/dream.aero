<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Bill
 *
 * @property int $id
 * @property string|null $number номер счета
 * @property int $payment_method_id способ оплаты
 * @property int $status_id статус
 * @property int $amount сумма счета
 * @property string|null $uuid
 * @property \datetime|null $payed_at дата проведения платежа
 * @property \datetime|null $link_sent_at
 * @property int $user_id пользователь
 * @property array|null $data_json дополнительная информация
 * @property \datetime|null $created_at
 * @property \datetime|null $updated_at
 * @property \datetime|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Deal[] $deals
 * @property-read int|null $deals_count
 * @property-read \App\Models\PaymentMethod|null $paymentMethod
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @property-read \App\Models\Status|null $status
 * @method static \Illuminate\Database\Eloquent\Builder|Bill newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bill newQuery()
 * @method static \Illuminate\Database\Query\Builder|Bill onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Bill query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereDataJson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereLinkSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill wherePayedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereUuid($value)
 * @method static \Illuminate\Database\Query\Builder|Bill withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Bill withoutTrashed()
 * @mixin \Eloquent
 * @property int $contractor_id
 * @property-read \App\Models\Contractor|null $contractor
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereContractorId($value)
 * @property int $deal_id
 * @property int $currency_id
 * @property-read \App\Models\Currency|null $currency
 * @property-read \App\Models\Deal|null $deal
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereDealId($value)
 * @property int $location_id локация, по которой был выставлен счет
 * @property-read \App\Models\Location|null $location
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereLocationId($value)
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereAeroflotBonusAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereAeroflotCardNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereAeroflotState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereAeroflotStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereAeroflotTransactionOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereAeroflotTransactionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereSuccessPaymentSentAt($value)
 * @property \Illuminate\Support\Carbon|null $aeroflot_transaction_created_at
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereAeroflotTransactionCreatedAt($value)
 * @property float $tax
 * @property float $total_amount
 * @property int $city_id
 * @property-read \App\Models\City|null $city
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereTotalAmount($value)
 * @property \Illuminate\Support\Carbon|null $receipt_sent_at
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereReceiptSentAt($value)
 */
	class Bill extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Certificate
 *
 * @property int $id
 * @property string|null $number номер
 * @property int $status_id статус
 * @property int $city_id город
 * @property int $product_id продукт
 * @property string|null $uuid uuid
 * @property \datetime|null $expire_at срок окончания действия сертификата
 * @property array|null $data_json дополнительная информация
 * @property \datetime|null $created_at
 * @property \datetime|null $updated_at
 * @property \datetime|null $deleted_at
 * @property-read \App\Models\City|null $city
 * @property-read \App\Models\Product|null $product
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @property-read \App\Models\Status|null $status
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate newQuery()
 * @method static \Illuminate\Database\Query\Builder|Certificate onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate query()
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereDataJson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereExpireAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereUuid($value)
 * @method static \Illuminate\Database\Query\Builder|Certificate withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Certificate withoutTrashed()
 * @mixin \Eloquent
 * @property \datetime|null $sent_at
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereCertificateSentAt($value)
 * @property-read \App\Models\Deal|null $deal
 */
	class Certificate extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\City
 *
 * @property int $id
 * @property string $name наименование
 * @property string $alias алиас
 * @property string|null $timezone временная зона
 * @property int $sort сортировка
 * @property bool $is_active признак активности
 * @property array|null $data_json дополнительная информация: часовой пояс
 * @property \datetime|null $created_at
 * @property \datetime|null $updated_at
 * @property \datetime|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Location[] $locations
 * @property-read int|null $locations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Promocode[] $promocodes
 * @property-read int|null $promocodes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @method static \Illuminate\Database\Eloquent\Builder|City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City newQuery()
 * @method static \Illuminate\Database\Query\Builder|City onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|City query()
 * @method static \Illuminate\Database\Eloquent\Builder|City whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereDataJson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|City withTrashed()
 * @method static \Illuminate\Database\Query\Builder|City withoutTrashed()
 * @mixin \Eloquent
 * @property string|null $email E-mail
 * @property string|null $phone телефон
 * @method static \Illuminate\Database\Eloquent\Builder|City whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City wherePayAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereNameEn($value)
 * @property int $currency_id
 * @property string $version версия
 * @property-read \App\Models\Currency|null $currency
 * @method static \Illuminate\Database\Eloquent\Builder|City whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereVersion($value)
 */
	class City extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CityProduct
 *
 * @property-read \App\Models\Discount $discount
 * @method static \Illuminate\Database\Eloquent\Builder|CityProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CityProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CityProduct query()
 * @mixin \Eloquent
 * @property \datetime $created_at
 * @property \datetime $updated_at
 * @property-read \App\Models\Currency|null $currency
 */
	class CityProduct extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CityPromocode
 *
 * @method static \Illuminate\Database\Eloquent\Builder|CityPromocode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CityPromocode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CityPromocode query()
 * @mixin \Eloquent
 * @property \datetime $created_at
 * @property \datetime $updated_at
 */
	class CityPromocode extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Content
 *
 * @property int $id
 * @property string $title заголовок
 * @property string $alias алиас
 * @property string|null $preview_text аннотация
 * @property string|null $detail_text контент
 * @property int $parent_id родитель
 * @property string|null $meta_title meta Title
 * @property string|null $meta_description meta Description
 * @property bool $is_active признак активности
 * @property array|null $data_json дополнительная информация
 * @property \datetime|null $published_at дата публикации
 * @property \datetime|null $created_at
 * @property \datetime|null $updated_at
 * @property \datetime|null $deleted_at
 * @property-read Content|null $parent
 * @method static \Illuminate\Database\Eloquent\Builder|Content newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Content newQuery()
 * @method static \Illuminate\Database\Query\Builder|Content onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Content query()
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereDataJson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereDetailText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content wherePreviewText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Content withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Content withoutTrashed()
 * @mixin \Eloquent
 * @property-read \App\Models\City|null $city
 * @property int $city_id город
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereCityId($value)
 * @property float $rating_value
 * @property int $rating_count
 * @property string|null $rating_ips
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereRatingCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereRatingIps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereRatingValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereMetaDescriptionEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereMetaTitleEn($value)
 * @property string $version версия
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereVersion($value)
 * @property \Illuminate\Support\Carbon|null $published_end_at
 * @method static \Illuminate\Database\Eloquent\Builder|Content wherePublishedEndAt($value)
 */
	class Content extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Contractor
 *
 * @property int $id
 * @property string $name имя
 * @property string|null $lastname фамилия
 * @property \datetime|null $birthdate дата рождения
 * @property string|null $phone основной номер телефона
 * @property string $email основной e-mail
 * @property string|null $password пароль в md5
 * @property string|null $remember_token
 * @property int $city_id город, к которому привязан контрагент
 * @property int $discount_id скидка
 * @property int $user_id пользователь
 * @property bool $is_active признак активности
 * @property \datetime|null $last_auth_at дата последней по времени авторизации
 * @property string|null $source источник
 * @property string|null $uuid uuid
 * @property bool $is_subscribed подписан на рассылку
 * @property array|null $data_json дополнительная информация
 * @property \datetime|null $created_at
 * @property \datetime|null $updated_at
 * @property \datetime|null $deleted_at
 * @property-read \App\Models\City|null $city
 * @property-read \App\Models\Discount|null $discount
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Token[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor newQuery()
 * @method static \Illuminate\Database\Query\Builder|Contractor onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor query()
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereBirthdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereDataJson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereDiscountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereIsSubscribed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereLastAuthAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contractor whereUuid($value)
 * @method static \Illuminate\Database\Query\Builder|Contractor withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Contractor withoutTrashed()
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Promocode[] $contractorPromocodes
 * @property-read int|null $contractor_promocodes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Promocode[] $promocodes
 * @property-read int|null $promocodes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bill[] $bills
 * @property-read int|null $bills_count
 */
	class Contractor extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ContractorPromocode
 *
 * @property \datetime $created_at
 * @property \datetime $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ContractorPromocode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContractorPromocode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContractorPromocode query()
 * @mixin \Eloquent
 */
	class ContractorPromocode extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Currency
 *
 * @property int $id
 * @property string $name наименование
 * @property string $alias alias
 * @property \datetime|null $created_at
 * @property \datetime|null $updated_at
 * @property \datetime|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @method static \Illuminate\Database\Eloquent\Builder|Currency newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency newQuery()
 * @method static \Illuminate\Database\Query\Builder|Currency onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency query()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Currency withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Currency withoutTrashed()
 * @mixin \Eloquent
 */
	class Currency extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Deal
 *
 * @property int $id
 * @property string|null $number номер
 * @property int $status_id статус
 * @property int $contractor_id контрагент
 * @property string $name имя
 * @property string $phone номер телефона
 * @property string $email e-mail
 * @property int $city_id город, в котором будет осуществлен полет
 * @property string|null $source источник
 * @property int $user_id пользователь
 * @property array|null $data_json дополнительная информация
 * @property \datetime|null $created_at
 * @property \datetime|null $updated_at
 * @property \datetime|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bill[] $bills
 * @property-read int|null $bills_count
 * @property-read \App\Models\Certificate|null $certificate
 * @property-read \App\Models\City|null $city
 * @property-read \App\Models\Contractor $contractor
 * @property-read \App\Models\Event|null $event
 * @property-read \App\Models\Location|null $location
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\Promo|null $promo
 * @property-read \App\Models\Promocode|null $promocode
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @property-read \App\Models\Status|null $status
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Deal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Deal newQuery()
 * @method static \Illuminate\Database\Query\Builder|Deal onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Deal query()
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereCertificateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereCertificateSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereContractorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereDataJson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereFlightAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereInviteSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereIsCertificatePurchase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereIsUnified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal wherePromoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal wherePromocodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Deal withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Deal withoutTrashed()
 * @mixin \Eloquent
 * @property int $flight_simulator_id
 * @property-read \App\Models\FlightSimulator|null $simulator
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereFlightSimulatorId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Event[] $events
 * @property-read int|null $events_count
 * @property string|null $uuid
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereUuid($value)
 * @property string|null $roistat номер визита Roistat
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereRoistat($value)
 * @property int $location_id
 * @property int $product_id
 * @property int $certificate_id
 * @property float $amount
 * @property float $tax
 * @property float $total_amount
 * @property int $currency_id
 * @property int $promo_id
 * @property int $promocode_id
 * @property bool $is_certificate_purchase
 * @property-read \App\Models\Currency|null $currency
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Deal whereTotalAmount($value)
 */
	class Deal extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Discount
 *
 * @property int $id
 * @property int|null $value размер скидки
 * @property bool $is_fixed фиксированная скидка
 * @property bool $is_active признак активности
 * @property \datetime|null $created_at
 * @property \datetime|null $updated_at
 * @property \datetime|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @method static \Illuminate\Database\Eloquent\Builder|Discount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Discount newQuery()
 * @method static \Illuminate\Database\Query\Builder|Discount onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Discount query()
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereIsFixed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|Discount withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Discount withoutTrashed()
 * @mixin \Eloquent
 * @property string|null $alias алиас
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereAlias($value)
 * @property int $currency_id
 * @property-read \App\Models\Currency|null $currency
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereCurrencyId($value)
 */
	class Discount extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Event
 *
 * @property int $id
 * @property string $event_type тип события
 * @property int $contractor_id Контрагент
 * @property int $deal_id сделка
 * @property int $city_id город, в котором будет осуществлен полет
 * @property int $location_id локация, на которой будет осуществлен полет
 * @property int $flight_simulator_id авиатренажер, на котором будет осуществлен полет
 * @property int $user_id
 * @property \datetime|null $start_at дата и время начала события
 * @property \datetime|null $stop_at дата и время окончания события
 * @property int $extra_time дополнительное время
 * @property int $is_repeated_flight признак повторного полета
 * @property int $is_unexpected_flight признак спонтанного полета
 * @property string|null $notification_type способ оповещения контрагента о полете
 * @property int $pilot_assessment оценка пилота
 * @property int $admin_assessment оценка админа
 * @property \datetime|null $simulator_up_at дата и время подъема платформы
 * @property \datetime|null $simulator_down_at дата и время опускания платформы
 * @property array|null $data_json дополнительная информация
 * @property \datetime|null $created_at
 * @property \datetime|null $updated_at
 * @property \datetime|null $deleted_at
 * @property-read \App\Models\City|null $city
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EventComment[] $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\Contractor|null $contractor
 * @property-read \App\Models\Deal|null $deal
 * @property-read \App\Models\Location|null $location
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @property-read \App\Models\FlightSimulator|null $simulator
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event newQuery()
 * @method static \Illuminate\Database\Query\Builder|Event onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Event query()
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereAdminAssessment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereContractorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDataJson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDealId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereEventType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereExtraTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereFlightSimulatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereIsRepeatedFlight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereIsUnexpectedFlight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereNotificationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event wherePilotAssessment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereSimulatorDownAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereSimulatorUpAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereStopAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Event withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Event withoutTrashed()
 * @mixin \Eloquent
 * @property bool $is_notified
 * @property \datetime|null $flight_invitation_sent_at
 * @property string|null $uuid
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereFlightInvitationSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereIsNotified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereUuid($value)
 * @property int $shift_admin_id
 * @property int $shift_pilot_id
 * @property string|null $description
 * @property int $pilot_id фактический пилот
 * @property int $test_pilot_id пилот тестового полета
 * @property int $employee_id сотрудник, осуществивший полет
 * @property-read \App\Models\User|null $employee
 * @property-read \App\Models\User|null $pilot
 * @property-read \App\Models\User|null $testPilot
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event wherePilotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereShiftAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereShiftPilotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereTestPilotId($value)
 * @property string|null $promocode_sent_at дата и время последней отправки промокода контрагенту
 * @property int $text_pilot_id тестовый полет пилота
 * @method static \Illuminate\Database\Eloquent\Builder|Event wherePromocodeSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereTextPilotId($value)
 */
	class Event extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\EventComment
 *
 * @property int $id
 * @property string $name комментарий
 * @property int $event_id событие
 * @property int $created_by кто создал
 * @property int $updated_by кто изменил
 * @property \datetime|null $created_at
 * @property \datetime|null $updated_at
 * @property \datetime|null $deleted_at
 * @property-read \App\Models\Event $event
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @method static \Illuminate\Database\Eloquent\Builder|EventComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EventComment newQuery()
 * @method static \Illuminate\Database\Query\Builder|EventComment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|EventComment query()
 * @method static \Illuminate\Database\Eloquent\Builder|EventComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventComment whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventComment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventComment whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventComment whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventComment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventComment whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|EventComment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|EventComment withoutTrashed()
 * @mixin \Eloquent
 * @property-read \App\Models\User|null $createdUser
 * @property-read \App\Models\User|null $updatedUser
 */
	class EventComment extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\FlightSimulator
 *
 * @property int $id
 * @property string $name наименование авиатренажера
 * @property string $alias алиас
 * @property bool $is_active признак активности
 * @property \datetime|null $created_at
 * @property \datetime|null $updated_at
 * @property \datetime|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Location[] $locations
 * @property-read int|null $locations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @method static \Illuminate\Database\Eloquent\Builder|FlightSimulator newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FlightSimulator newQuery()
 * @method static \Illuminate\Database\Query\Builder|FlightSimulator onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|FlightSimulator query()
 * @method static \Illuminate\Database\Eloquent\Builder|FlightSimulator whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlightSimulator whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlightSimulator whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlightSimulator whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlightSimulator whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlightSimulator whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FlightSimulator whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|FlightSimulator withTrashed()
 * @method static \Illuminate\Database\Query\Builder|FlightSimulator withoutTrashed()
 * @mixin \Eloquent
 */
	class FlightSimulator extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\LegalEntity
 *
 * @property int $id
 * @property string $name наименование юр.лица
 * @property string $alias алиас
 * @property array|null $data_json дополнительная информация
 * @property bool $is_active признак активности
 * @property \datetime|null $created_at
 * @property \datetime|null $updated_at
 * @property \datetime|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity newQuery()
 * @method static \Illuminate\Database\Query\Builder|LegalEntity onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity query()
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereDataJson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|LegalEntity withTrashed()
 * @method static \Illuminate\Database\Query\Builder|LegalEntity withoutTrashed()
 * @mixin \Eloquent
 */
	class LegalEntity extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Location
 *
 * @property int $id
 * @property string $name наименование
 * @property string $alias alias
 * @property int $city_id город, в котором находится локация
 * @property int $sort сортировка
 * @property array|null $data_json дополнительная информация
 * @property bool $is_active признак активности
 * @property \datetime|null $created_at
 * @property \datetime|null $updated_at
 * @property \datetime|null $deleted_at
 * @property-read \App\Models\City $city
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $user
 * @property-read int|null $user_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FlightSimulator[] $simulator
 * @property-read int|null $simulator_count
 * @method static \Illuminate\Database\Eloquent\Builder|Location newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Location newQuery()
 * @method static \Illuminate\Database\Query\Builder|Location onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Location query()
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereDataJson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Location withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Location withoutTrashed()
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FlightSimulator[] $simulators
 * @property-read int|null $simulators_count
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereNameEn($value)
 * @property string|null $pay_account_number номер счета в платежной системе
 * @method static \Illuminate\Database\Eloquent\Builder|Location wherePayAccountNumber($value)
 */
	class Location extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\LocationFlightSimulator
 *
 * @method static \Illuminate\Database\Eloquent\Builder|LocationFlightSimulator newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LocationFlightSimulator newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LocationFlightSimulator query()
 * @mixin \Eloquent
 * @property \datetime $created_at
 * @property \datetime $updated_at
 */
	class LocationFlightSimulator extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Operation
 *
 * @property int $id
 * @property string|null $type тип операции
 * @property int $payment_method_id способ оплаты
 * @property float $amount сумма
 * @property int $currency_id валюта
 * @property int $city_id город
 * @property int $location_id локация
 * @property \Illuminate\Support\Carbon|null $operated_at дата операции
 * @property int $user_id пользователь
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\City|null $city
 * @property-read \App\Models\Currency|null $currency
 * @property-read \App\Models\Location|null $location
 * @property-read \App\Models\PaymentMethod|null $paymentMethod
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Operation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Operation newQuery()
 * @method static \Illuminate\Database\Query\Builder|Operation onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Operation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereOperatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operation wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Operation withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Operation withoutTrashed()
 * @mixin \Eloquent
 * @property array|null $data_json
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereDataJson($value)
 * @property int $operation_type_id тип операции
 * @property-read \App\Models\OperationType|null $operationType
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereOperationTypeId($value)
 */
	class Operation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\OperationType
 *
 * @method static \Illuminate\Database\Eloquent\Builder|OperationType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OperationType newQuery()
 * @method static \Illuminate\Database\Query\Builder|OperationType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|OperationType query()
 * @method static \Illuminate\Database\Query\Builder|OperationType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|OperationType withoutTrashed()
 */
	class OperationType extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PaymentMethod
 *
 * @property int $id
 * @property string $name наименование способа оплаты
 * @property string $alias алиас
 * @property bool $is_active признак активности
 * @property \datetime|null $created_at
 * @property \datetime|null $updated_at
 * @property \datetime|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod newQuery()
 * @method static \Illuminate\Database\Query\Builder|PaymentMethod onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|PaymentMethod withTrashed()
 * @method static \Illuminate\Database\Query\Builder|PaymentMethod withoutTrashed()
 * @mixin \Eloquent
 */
	class PaymentMethod extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $name наименование продукта
 * @property string $alias алиас
 * @property int $product_type_id тип продукта
 * @property int $user_id пользователь
 * @property int $duration длительность полёта, мин.
 * @property array|null $data_json дополнительная информация
 * @property \datetime|null $created_at
 * @property \datetime|null $updated_at
 * @property \datetime|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\City[] $cities
 * @property-read int|null $cities_count
 * @property-read \App\Models\User|null $user
 * @property-read \App\Models\ProductType|null $productType
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Query\Builder|Product onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDataJson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Product withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Product withoutTrashed()
 * @mixin \Eloquent
 * @property int $employee_id пилот
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereEmployeeId($value)
 * @property string|null $public_name
 * @property bool $is_active
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePublicName($value)
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProductType
 *
 * @property int $id
 * @property string $name наименование типа продукта
 * @property string $alias алиас
 * @property bool $is_tariff является ли продукт тарифом
 * @property int $sort сортировка
 * @property bool $is_active признак активности
 * @property array|null $data_json дополнительная информация
 * @property \datetime|null $created_at
 * @property \datetime|null $updated_at
 * @property \datetime|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @method static \Illuminate\Database\Eloquent\Builder|ProductType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductType newQuery()
 * @method static \Illuminate\Database\Query\Builder|ProductType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductType query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductType whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductType whereDataJson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductType whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductType whereIsTariff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductType whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|ProductType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ProductType withoutTrashed()
 * @mixin \Eloquent
 * @property int $tax
 * @method static \Illuminate\Database\Eloquent\Builder|ProductType whereTax($value)
 */
	class ProductType extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Promo
 *
 * @property int $id
 * @property string $name наименование
 * @property int $discount_id скидка
 * @property string|null $preview_text анонс
 * @property string|null $detail_text описание
 * @property int $city_id город, к которому относится акция
 * @property bool $is_published для публикации
 * @property bool $is_active признак активности
 * @property \datetime|null $active_from_at дата начала активности
 * @property \datetime|null $active_to_at дата окончания активности
 * @property \datetime|null $created_at
 * @property \datetime|null $updated_at
 * @property \datetime|null $deleted_at
 * @property-read \App\Models\City|null $city
 * @property-read \App\Models\Discount|null $discount
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @method static \Illuminate\Database\Eloquent\Builder|Promo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Promo newQuery()
 * @method static \Illuminate\Database\Query\Builder|Promo onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Promo query()
 * @method static \Illuminate\Database\Eloquent\Builder|Promo whereActiveFromAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promo whereActiveToAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promo whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promo whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promo whereDetailText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promo whereDiscountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promo whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promo whereIsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promo whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promo wherePreviewText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Promo withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Promo withoutTrashed()
 * @mixin \Eloquent
 * @property string|null $alias
 * @method static \Illuminate\Database\Eloquent\Builder|Promo whereAlias($value)
 * @property array|null $data_json дополнительная информация
 * @method static \Illuminate\Database\Eloquent\Builder|Promo whereDataJson($value)
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @method static \Illuminate\Database\Eloquent\Builder|Promo whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promo whereMetaTitle($value)
 */
	class Promo extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Promocode
 *
 * @property int $id
 * @property string $number промокод
 * @property int $discount_id скидка
 * @property bool $is_active признак активности
 * @property \datetime|null $active_from_at дата начала активности
 * @property \datetime|null $active_to_at дата окончания активности
 * @property array|null $data_json дополнительная информация
 * @property \datetime|null $created_at
 * @property \datetime|null $updated_at
 * @property \datetime|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\City[] $cities
 * @property-read int|null $cities_count
 * @property-read \App\Models\Discount|null $discount
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @method static \Illuminate\Database\Eloquent\Builder|Promocode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Promocode newQuery()
 * @method static \Illuminate\Database\Query\Builder|Promocode onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Promocode query()
 * @method static \Illuminate\Database\Eloquent\Builder|Promocode whereActiveFromAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promocode whereActiveToAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promocode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promocode whereDataJson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promocode whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promocode whereDiscountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promocode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promocode whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promocode whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promocode whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Promocode withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Promocode withoutTrashed()
 * @mixin \Eloquent
 * @property string|null $uuid uuid
 * @method static \Illuminate\Database\Eloquent\Builder|Promocode whereUuid($value)
 * @property string|null $type
 * @property int $contractor_id
 * @property int $location_id
 * @property int $flight_simulator_id
 * @property \datetime|null $sent_at
 * @property-read \App\Models\Contractor|null $contractor
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Contractor[] $contractors
 * @property-read int|null $contractors_count
 * @method static \Illuminate\Database\Eloquent\Builder|Promocode whereContractorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promocode whereFlightSimulatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promocode whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promocode whereSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Promocode whereType($value)
 * @property-read \App\Models\Location|null $location
 */
	class Promocode extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Status
 *
 * @property int $id
 * @property string $name наименование
 * @property string $alias алиас
 * @property string $type тип сущности: контрагент, заказ, сделка, счет, платеж, сертификат
 * @property int $flight_time время налета
 * @property int $sort сортировка
 * @property bool $is_active признак активности
 * @property array|null $data_json дополнительная информация
 * @property \datetime|null $created_at
 * @property \datetime|null $updated_at
 * @property \datetime|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @method static \Illuminate\Database\Eloquent\Builder|Status newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Status newQuery()
 * @method static \Illuminate\Database\Query\Builder|Status onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Status query()
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereDataJson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereFlightTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Status withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Status withoutTrashed()
 * @mixin \Eloquent
 * @property int $discount_id скидка
 * @property-read \App\Models\Discount|null $discount
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereDiscountId($value)
 */
	class Status extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Tip
 *
 * @property int $id
 * @property int $deal_id сделка
 * @property float $amount сумма
 * @property int $currency_id валюта
 * @property int $city_id город
 * @property \Illuminate\Support\Carbon|null $received_at дата получения
 * @property int $admin_id
 * @property int $pilot_id
 * @property int $user_id пользователь
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $admin
 * @property-read \App\Models\City|null $city
 * @property-read \App\Models\Currency|null $currency
 * @property-read \App\Models\Deal|null $deal
 * @property-read \App\Models\User|null $pilot
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Tip newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tip newQuery()
 * @method static \Illuminate\Database\Query\Builder|Tip onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Tip query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereDealId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip wherePilotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereReceivedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Tip withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Tip withoutTrashed()
 * @mixin \Eloquent
 * @property-read \App\Models\Location|null $location
 * @property-read \App\Models\PaymentMethod|null $paymentMethod
 * @property string|null $source источник
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereSource($value)
 * @property int $location_id
 * @property int $payment_method_id
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip wherePaymentMethodId($value)
 */
	class Tip extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $role
 * @property int $city_id город
 * @property int $location_id локация
 * @property bool $enable
 * @property string|null $remember_token
 * @property \datetime|null $created_at
 * @property \datetime|null $updated_at
 * @property \datetime|null $deleted_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Query\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEnable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withoutTrashed()
 * @mixin \Eloquent
 * @property-read \App\Models\City $city
 * @property-read \App\Models\Location $location
 * @property string|null $lastname
 * @property string|null $middlename
 * @property string|null $data_json
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDataJson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMiddlename($value)
 * @property \Illuminate\Support\Carbon|null $birthdate дата рождения
 * @property string|null $phone Телефон
 * @property string|null $position должность
 * @property int $is_reserved признак резервного сотрудника
 * @property int $is_official признак официального трудоустройства
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBirthdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsOfficial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsReserved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePosition($value)
 */
	class User extends \Eloquent {}
}

