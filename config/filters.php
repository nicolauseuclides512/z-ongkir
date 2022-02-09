<?php

return [
    'asset_country' => [
        'all' => \App\Models\AssetCountry::STATUS_ALL,
        'inactive' => \App\Models\AssetCountry::STATUS_INACTIVE,
        'active' => \App\Models\AssetCountry::STATUS_ACTIVE
    ],
    'asset_province' => [
        'all' => \App\Models\AssetProvince::STATUS_ALL,
        'inactive' => \App\Models\AssetProvince::STATUS_INACTIVE,
        'active' => \App\Models\AssetProvince::STATUS_ACTIVE
    ],
    'asset_district' => [
        'all' => \App\Models\AssetDistrict::STATUS_ALL,
        'inactive' => \App\Models\AssetDistrict::STATUS_INACTIVE,
        'active' => \App\Models\AssetDistrict::STATUS_ACTIVE
    ],
    'asset_region' => [
        'all' => \App\Models\AssetRegion::STATUS_ALL,
        'inactive' => \App\Models\AssetRegion::STATUS_INACTIVE,
        'active' => \App\Models\AssetRegion::STATUS_ACTIVE
    ],

    'asset_carriers' => [
        'all' => \App\Models\AssetCarrier::STATUS_ALL,
        'inactive' => \App\Models\AssetCarrier::STATUS_INACTIVE,
        'active' => \App\Models\AssetCarrier::STATUS_ACTIVE
    ],

];