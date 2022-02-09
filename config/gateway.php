<?php
return [

    "timeout" => 60.0,

    "connect_timeout" => 60.0,

    "connection" => [

        "raja_ongkir" => [
            "api_url" => env("RAJAONGKIR_GATEWAY_SERVICE", "http://pro.rajaongkir.com/api"),
            "api_key" => env("RAJAONGKIR_API_KEY", "c29168581f10f43d3eede488864a573c")
        ],

        "lion_parcel" => [
            "api_url" => env("LIONPARCEL_GATEWAY_SERVICE", "http://training.lionparcel.com/ewebportal")
        ]

    ]
];
