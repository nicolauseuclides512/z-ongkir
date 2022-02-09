<?php

use App\Models\AssetCarrier;
use App\Models\AssetPaymentMethod;
use App\Models\AssetWeightUnit;
use App\Utils\CsvConverter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


/**
 * @author Jehan Afwazi Ahmad <jee.archer@gmail.com>.
 */
class AssetTableSeeder extends Seeder
{

    public function run()
    {
        #COUNTRY
        $countriesCsvFile = database_path("resources/rajaongkir_negara.csv");
        $countriesCsvResult = \App\Utils\CsvConverter::csvToArray($countriesCsvFile);

        array_map(function ($x) {
            $country = \App\Models\AssetCountry::inst();
            $country->id = $x['country_id'];
            $country->code = null;
            $country->name = ucfirst(strtolower($x["country_name"]));
            $country->status = $x['status'];
            if (!$country->save())
                Log::error("Country. [ $country->id ]" . json_encode($country->errors));
        }, $countriesCsvResult);

        printf('Seed country done ');

        $countryId = 236;

        #PROVINCE
        $provincesCsvFile = database_path("resources/rajaongkir_propinsi.csv");
        $provincesCsvResult = \App\Utils\CsvConverter::csvToArray($provincesCsvFile);

        array_map(function ($x) use ($countryId) {
            $province = \App\Models\AssetProvince::inst();
            $province->country_id = $countryId;
            $province->id = $x["province_id"];
            $province->name = $x["province_name"];
            $province->status = true;
            if (!$province->save())
                Log::error("province failed. [ $province->id ] " . json_encode($province->errors));
        }, $provincesCsvResult);

        printf('Seed province done ');

        #DISTRICT
        $districtsCsvFile = database_path("resources/rajaongkir_kabupaten.csv");
        $districtsCsvResult = \App\Utils\CsvConverter::csvToArray($districtsCsvFile);

        array_map(function ($x) {
            $district = \App\Models\AssetDistrict::inst();
            $district->province_id = $x['province_id'];
            $district->id = $x['district_id'];
            $district->name = $x['district_name'];
            $district->type = "city";
            $district->zip = $x['postal_code'];
            $district->status = true;
            if (!$district->save())
                Log::error("district failed. [ $district->id ] " . json_encode($district->errors));
        }, $districtsCsvResult);

        printf('Seed district done ');

        #REGION
        $regionsCsvFile = database_path("resources/rajaongkir_kecamatan.csv");
        $regionsCsvResult = \App\Utils\CsvConverter::csvToArray($regionsCsvFile);

        array_map(function ($x) {
            $region = \App\Models\AssetRegion::inst();
            $region->district_id = $x['district_id'];
            $region->id = $x['region_id'];
            $region->name = $x['region_name'];
            $region->zip = null;
            $region->type = "subdistrict";
            $region->status = true;
            if (!$region->save())
                Log::error("region failed. [ $region->id ]" . json_encode($region->errors));
        }, $regionsCsvResult);

        printf('Seed region done ');

        #ASSET CARRIER
        $carrierDataCsvFile = database_path("resources/rajaongkir_kurir.csv");
        $carrierDataResult = CsvConverter::csvToArray($carrierDataCsvFile);

        array_map(function ($x) {
            $carrier = AssetCarrier::inst();
            $carrier->logo = $x['logo'];
            $carrier->name = $x['name'];
            $carrier->code = $x['code'];
            $carrier->status = $x['status'];
            if (!$carrier->save()) {
                Log::error('setup carrier failed ' . json_encode($carrier->errors));
            }
        }, $carrierDataResult);


        DB::table('asset_regions')
            ->update(['type' => 'subdistrict']);

        DB::table('asset_districts')
            ->update(['type' => 'city']);

        DB::transaction(function () {
            DB::update('
        update asset_regions set is_priority = 1
where (id = 5 and district_id = 1)
or (id = 14 and district_id = 2)
or (id = 30 and district_id = 3)
or (id = 49 and district_id = 4)
or (id = 68 and district_id = 5)
or (id = 79 and district_id = 6)
or (id = 88 and district_id = 7)
or (id = 113 and district_id = 9)
or (id = 130 and district_id = 10)
or (id = 158 and district_id = 11)
or (id = 183 and district_id = 12)
or (id = 211 and district_id = 14)
or (id = 222 and district_id = 15)
or (id = 239 and district_id = 16)
or (id = 262 and district_id = 17)
or (id = 269 and district_id = 18)
or (id = 272 and district_id = 19)
or (id = 280 and district_id = 20)
or (id = 290 and district_id = 21)
or (id = 337 and district_id = 22)
or (id = 343 and district_id = 23)
or (id = 378 and district_id = 24)
or (id = 394 and district_id = 25)
or (id = 407 and district_id = 26)
or (id = 433 and district_id = 27)
or (id = 435 and district_id = 28)
or (id = 446 and district_id = 29)
or (id = 448 and district_id = 30)
or (id = 455 and district_id = 31)
or (id = 472 and district_id = 32)
or (id = 484 and district_id = 33)
or (id = 495 and district_id = 34)
or (id = 504 and district_id = 36)
or (id = 510 and district_id = 37)
or (id = 529 and district_id = 38)
or (id = 539 and district_id = 39)
or (id = 589 and district_id = 41)
or (id = 601 and district_id = 42)
or (id = 634 and district_id = 43)
or (id = 651 and district_id = 45)
or (id = 667 and district_id = 47)
or (id = 673 and district_id = 48)
or (id = 687 and district_id = 49)
or (id = 705 and district_id = 50)
or (id = 708 and district_id = 51)
or (id = 712 and district_id = 52)
or (id = 750 and district_id = 55)
or (id = 765 and district_id = 56)
or (id = 770 and district_id = 57)
or (id = 774 and district_id = 58)
or (id = 808 and district_id = 60)
or (id = 815 and district_id = 61)
or (id = 832 and district_id = 62)
or (id = 845 and district_id = 63)
or (id = 865 and district_id = 65)
or (id = 890 and district_id = 66)
or (id = 895 and district_id = 67)
or (id = 928 and district_id = 68)
or (id = 929 and district_id = 69)
or (id = 935 and district_id = 70)
or (id = 941 and district_id = 71)
or (id = 993 and district_id = 74)
or (id = 1000 and district_id = 76)
or (id = 1020 and district_id = 77)
or (id = 1028 and district_id = 78)
or (id = 1062 and district_id = 79)
or (id = 1070 and district_id = 80)
or (id = 1146 and district_id = 85)
or (id = 1150 and district_id = 86)
or (id = 1211 and district_id = 88)
or (id = 1243 and district_id = 91)
or (id = 1261 and district_id = 92)
or (id = 1276 and district_id = 93)
or (id = 1290 and district_id = 95)
or (id = 1307 and district_id = 96)
or (id = 1309 and district_id = 97)
or (id = 1341 and district_id = 99)
or (id = 1364 and district_id = 101)
or (id = 1375 and district_id = 102)
or (id = 1381 and district_id = 103)
or (id = 1409 and district_id = 104)
or (id = 1440 and district_id = 105)
or (id = 1462 and district_id = 106)
or (id = 1469 and district_id = 107)
or (id = 1504 and district_id = 108)
or (id = 1512 and district_id = 109)
or (id = 1522 and district_id = 110)
or (id = 1534 and district_id = 111)
or (id = 1547 and district_id = 112)
or (id = 1560 and district_id = 113)
or (id = 1573 and district_id = 114)
or (id = 1580 and district_id = 115)
or (id = 1593 and district_id = 116)
or (id = 1600 and district_id = 117)
or (id = 1609 and district_id = 118)
or (id = 1619 and district_id = 119)
or (id = 1634 and district_id = 120)
or (id = 1648 and district_id = 121)
or (id = 1652 and district_id = 122)
or (id = 1679 and district_id = 123)
or (id = 1684 and district_id = 124)
or (id = 1701 and district_id = 125)
or (id = 1728 and district_id = 126)
or (id = 1754 and district_id = 127)
or (id = 1765 and district_id = 128)
or (id = 1791 and district_id = 130)
or (id = 1803 and district_id = 131)
or (id = 1820 and district_id = 132)
or (id = 1835 and district_id = 133)
or (id = 1859 and district_id = 134)
or (id = 1882 and district_id = 135)
or (id = 1885 and district_id = 136)
or (id = 1904 and district_id = 138)
or (id = 1943 and district_id = 140)
or (id = 1948 and district_id = 141)
or (id = 1968 and district_id = 142)
or (id = 1979 and district_id = 143)
or (id = 1998 and district_id = 145)
or (id = 2007 and district_id = 146)
or (id = 2033 and district_id = 147)
or (id = 2046 and district_id = 148)
or (id = 2060 and district_id = 149)
or (id = 2088 and district_id = 151)
or (id = 2130 and district_id = 156)
or (id = 2156 and district_id = 158)
or (id = 2194 and district_id = 159)
or (id = 2211 and district_id = 160)
or (id = 2235 and district_id = 161)
or (id = 2251 and district_id = 163)
or (id = 2269 and district_id = 164)
or (id = 2286 and district_id = 165)
or (id = 2292 and district_id = 166)
or (id = 2313 and district_id = 167)
or (id = 2332 and district_id = 168)
or (id = 2361 and district_id = 169)
or (id = 2388 and district_id = 171)
or (id = 2424 and district_id = 173)
or (id = 2442 and district_id = 174)
or (id = 2464 and district_id = 175)
or (id = 2469 and district_id = 176)
or (id = 2482 and district_id = 177)
or (id = 2499 and district_id = 178)
or (id = 2531 and district_id = 180)
or (id = 2540 and district_id = 181)
or (id = 2557 and district_id = 182)
or (id = 2565 and district_id = 183)
or (id = 2586 and district_id = 185)
or (id = 2588 and district_id = 186)
or (id = 2617 and district_id = 188)
or (id = 2622 and district_id = 189)
or (id = 2641 and district_id = 191)
or (id = 2674 and district_id = 193)
or (id = 2704 and district_id = 195)
or (id = 2736 and district_id = 196)
or (id = 2753 and district_id = 198)
or (id = 2775 and district_id = 199)
or (id = 2807 and district_id = 200)
or (id = 2836 and district_id = 201)
or (id = 2874 and district_id = 205)
or (id = 2895 and district_id = 206)
or (id = 2918 and district_id = 208)
or (id = 2927 and district_id = 209)
or (id = 2941 and district_id = 210)
or (id = 2963 and district_id = 211)
or (id = 2989 and district_id = 212)
or (id = 2998 and district_id = 213)
or (id = 3017 and district_id = 214)
or (id = 3041 and district_id = 215)
or (id = 3056 and district_id = 216)
or (id = 3091 and district_id = 220)
or (id = 3123 and district_id = 222)
or (id = 3141 and district_id = 223)
or (id = 3158 and district_id = 224)
or (id = 3180 and district_id = 225)
or (id = 3220 and district_id = 226)
or (id = 3237 and district_id = 227)
or (id = 3256 and district_id = 228)
or (id = 3280 and district_id = 229)
or (id = 3283 and district_id = 230)
or (id = 3321 and district_id = 232)
or (id = 3349 and district_id = 235)
or (id = 3364 and district_id = 237)
or (id = 3387 and district_id = 239)
or (id = 3406 and district_id = 240)
or (id = 3420 and district_id = 242)
or (id = 3433 and district_id = 243)
or (id = 3452 and district_id = 244)
or (id = 3473 and district_id = 245)
or (id = 3487 and district_id = 246)
or (id = 3501 and district_id = 247)
or (id = 3519 and district_id = 249)
or (id = 3532 and district_id = 250)
or (id = 3542 and district_id = 251)
or (id = 3569 and district_id = 252)
or (id = 3598 and district_id = 254)
or (id = 3613 and district_id = 255)
or (id = 3634 and district_id = 256)
or (id = 3645 and district_id = 257)
or (id = 3675 and district_id = 259)
or (id = 3714 and district_id = 262)
or (id = 3730 and district_id = 263)
or (id = 3746 and district_id = 265)
or (id = 3763 and district_id = 266)
or (id = 3770 and district_id = 267)
or (id = 3809 and district_id = 269)
or (id = 3813 and district_id = 270)
or (id = 3823 and district_id = 271)
or (id = 3832 and district_id = 272)
or (id = 3847 and district_id = 274)
or (id = 3878 and district_id = 276)
or (id = 3906 and district_id = 278)
or (id = 3931 and district_id = 279)
or (id = 3938 and district_id = 280)
or (id = 3970 and district_id = 281)
or (id = 3989 and district_id = 283)
or (id = 4005 and district_id = 284)
or (id = 4031 and district_id = 286)
or (id = 4051 and district_id = 287)
or (id = 4060 and district_id = 288)
or (id = 4088 and district_id = 290)
or (id = 4118 and district_id = 292)
or (id = 4146 and district_id = 294)
or (id = 4210 and district_id = 297)
or (id = 4219 and district_id = 298)
or (id = 4239 and district_id = 299)
or (id = 4257 and district_id = 300)
or (id = 4260 and district_id = 301)
or (id = 4312 and district_id = 304)
or (id = 4331 and district_id = 305)
or (id = 4354 and district_id = 306)
or (id = 4406 and district_id = 309)
or (id = 4426 and district_id = 311)
or (id = 4437 and district_id = 312)
or (id = 4456 and district_id = 313)
or (id = 4471 and district_id = 314)
or (id = 4493 and district_id = 315)
or (id = 4518 and district_id = 316)
or (id = 4528 and district_id = 317)
or (id = 4534 and district_id = 318)
or (id = 4557 and district_id = 320)
or (id = 4574 and district_id = 322)
or (id = 4586 and district_id = 323)
or (id = 4595 and district_id = 324)
or (id = 4599 and district_id = 325)
or (id = 4605 and district_id = 326)
or (id = 4609 and district_id = 327)
or (id = 4628 and district_id = 328)
or (id = 4635 and district_id = 329)
or (id = 4649 and district_id = 330)
or (id = 4680 and district_id = 331)
or (id = 4710 and district_id = 333)
or (id = 4718 and district_id = 334)
or (id = 4720 and district_id = 335)
or (id = 4730 and district_id = 336)
or (id = 4734 and district_id = 337)
or (id = 4747 and district_id = 338)
or (id = 4762 and district_id = 339)
or (id = 4791 and district_id = 341)
or (id = 4803 and district_id = 342)
or (id = 4818 and district_id = 343)
or (id = 4833 and district_id = 344)
or (id = 4883 and district_id = 347)
or (id = 4894 and district_id = 348)
or (id = 4910 and district_id = 349)
or (id = 4918 and district_id = 350)
or (id = 4932 and district_id = 351)
or (id = 4944 and district_id = 352)
or (id = 4952 and district_id = 353)
or (id = 4961 and district_id = 354)
or (id = 4964 and district_id = 355)
or (id = 4989 and district_id = 357)
or (id = 5008 and district_id = 358)
or (id = 5026 and district_id = 359)
or (id = 5046 and district_id = 361)
or (id = 5066 and district_id = 362)
or (id = 5082 and district_id = 363)
or (id = 5093 and district_id = 364)
or (id = 5101 and district_id = 365)
or (id = 5120 and district_id = 366)
or (id = 5127 and district_id = 367)
or (id = 5144 and district_id = 369)
or (id = 5165 and district_id = 370)
or (id = 5172 and district_id = 371)
or (id = 5195 and district_id = 374)
or (id = 5215 and district_id = 375)
or (id = 5229 and district_id = 376)
or (id = 5249 and district_id = 377)
or (id = 5256 and district_id = 378)
or (id = 5277 and district_id = 379)
or (id = 5296 and district_id = 380)
or (id = 5345 and district_id = 384)
or (id = 5352 and district_id = 386)
or (id = 5359 and district_id = 387)
or (id = 5374 and district_id = 388)
or (id = 5389 and district_id = 389)
or (id = 5403 and district_id = 390)
or (id = 5429 and district_id = 392)
or (id = 5441 and district_id = 393)
or (id = 5452 and district_id = 395)
or (id = 5454 and district_id = 396)
or (id = 5465 and district_id = 397)
or (id = 5496 and district_id = 398)
or (id = 5509 and district_id = 399)
or (id = 5522 and district_id = 400)
or (id = 5525 and district_id = 401)
or (id = 5572 and district_id = 403)
or (id = 5584 and district_id = 404)
or (id = 5602 and district_id = 406)
or (id = 5617 and district_id = 407)
or (id = 5641 and district_id = 409)
or (id = 5713 and district_id = 413)
or (id = 5725 and district_id = 414)
or (id = 5734 and district_id = 415)
or (id = 5759 and district_id = 417)
or (id = 5776 and district_id = 418)
or (id = 5793 and district_id = 419)
or (id = 5844 and district_id = 425)
or (id = 5861 and district_id = 426)
or (id = 5878 and district_id = 427)
or (id = 5909 and district_id = 428)
or (id = 5916 and district_id = 429)
or (id = 5958 and district_id = 430)
or (id = 5965 and district_id = 431)
or (id = 5976 and district_id = 432)
or (id = 5986 and district_id = 433)
or (id = 5989 and district_id = 434)
or (id = 5999 and district_id = 435)
or (id = 6008 and district_id = 436)
or (id = 6018 and district_id = 437)
or (id = 6053 and district_id = 438)
or (id = 6064 and district_id = 439)
or (id = 6082 and district_id = 440)
or (id = 6106 and district_id = 441)
or (id = 6126 and district_id = 443)
or (id = 6131 and district_id = 444)
or (id = 6162 and district_id = 445)
or (id = 6176 and district_id = 446)
or (id = 6188 and district_id = 447)
or (id = 6218 and district_id = 451)
or (id = 6265 and district_id = 454)
or (id = 6296 and district_id = 455)
or (id = 6309 and district_id = 456)
or (id = 6325 and district_id = 458)
or (id = 6340 and district_id = 459)
or (id = 6368 and district_id = 462)
or (id = 6383 and district_id = 463)
or (id = 6419 and district_id = 465)
or (id = 6432 and district_id = 467)
or (id = 6466 and district_id = 468)
or (id = 6476 and district_id = 469)
or (id = 6489 and district_id = 470)
or (id = 6490 and district_id = 471)
or (id = 6515 and district_id = 472)
or (id = 6520 and district_id = 473)
or (id = 6527 and district_id = 474)
or (id = 6554 and district_id = 475)
or (id = 6576 and district_id = 476)
or (id = 6585 and district_id = 477)
or (id = 6592 and district_id = 478)
or (id = 6611 and district_id = 479)
or (id = 6643 and district_id = 480)
or (id = 6653 and district_id = 481)
or (id = 6668 and district_id = 482)
or (id = 6707 and district_id = 484)
or (id = 6749 and district_id = 486)
or (id = 6770 and district_id = 487)
or (id = 6796 and district_id = 489)
or (id = 6805 and district_id = 490)
or (id = 6839 and district_id = 492)
or (id = 6860 and district_id = 494)
or (id = 6865 and district_id = 495)
or (id = 6874 and district_id = 496)
or (id = 6908 and district_id = 497)
or (id = 6924 and district_id = 498)
or (id = 6926 and district_id = 499)
or (id = 6992 and district_id = 501);
        ');
            echo 'updating and set is_priority done';
        });

    }
}