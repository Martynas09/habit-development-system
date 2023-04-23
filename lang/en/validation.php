<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute turi būti patvirtintas.',
    'accepted_if' => ':attribute turi būti patvirtintas, kai :other yra :value.',
    'active_url' => ':attribute nėra tinkamas URL.',
    'after' => ':attribute turi būti data po :date.',
    'after_or_equal' => ':attribute turi būti data po arba lygi :date.',
    'alpha' => ':attribute gali turėti tik raides.',
    'alpha_dash' => ':attribute gali turėti tik raides, skaičius, brūkšnelius ir pabrėžimus.',
    'alpha_num' => ':attribute gali turėti tik raides ir skaičius.',
    'array' => ':attribute turi būti masyvas.',
    'ascii' => ':attribute gali turėti tik vienabaites alfanumerines raides ir simbolius.',
    'before' => ':attribute turi būti data prieš :date.',
    'before_or_equal' => ':attribute turi būti data prieš arba lygi :date.',
    'between' => [
        'array' => ':attribute turi turėti tarp :min ir :max elementų.',
        'file' => ':attribute turi būti tarp :min ir :max kilobaitų.',
        'numeric' => ':attribute turi būti tarp :min ir :max.',
        'string' => ':attribute turi turėti tarp :min ir :max simbolių.',
    ],
    'boolean' => ':attribute laukas turi būti tiesa arba netiesa.',
    'confirmed' => ':attribute patvirtinimas nesutampa.',
    'current_password' => 'Neteisingas slaptažodis.',
    'date' => ':attribute nėra tinkama data.',
    'date_equals' => ':attribute turi būti data lygi :date.',
    'date_format' => ':attribute neatitinka formato :format.',
    'decimal' => ':attribute turi turėti :decimal dešimtainių skaičių.',
    'declined' => ':attribute turi būti atmestas.',
    'declined_if' => ':attribute turi būti atmestas, kai :other yra :value.',
    'different' => ':attribute ir :other turi būti skirtingi.',
    'digits' => ':attribute turi būti :digits skaitmenų.',
    'digits_between' => ':attribute turi būti tarp :min ir :max skaitmenų.',
    'dimensions' => ':attribute turi netinkamus paveikslėlio matmenis.',
    'distinct' => ':attribute laukas turi pasikartojančią reikšmę.',
    'doesnt_end_with' => ':attribute negali baigtis viena iš šių: :values.',
    'doesnt_start_with' => ':attribute negali prasidėti viena iš šių: :values.',
    'email' => ':attribute turi būti tinkamas el. pašto adresas.',
    'ends_with' => ':attribute turi baigtis viena iš šių: :values.',
    'enum' => 'Pasirinktas :attribute yra neteisingas.',
    'exists' => 'Pasirinktas :attribute yra neteisingas.',
    'file' => ':attribute turi būti failas.',
    'filled' => ':attribute laukas turi turėti reikšmę.',
    'gt' => [
        'array' => ':attribute turi turėti daugiau nei :value elementus.',
        'file' => ':attribute turi būti didesnis nei :value kilobaitai.',
        'numeric' => ':attribute turi būti didesnis nei :value.',
        'string' => ':attribute turi būti ilgesnis nei :value simboliai.',
    ],
    'gte' => [
        'array' => ':attribute turi turėti :value arba daugiau elementų.',
        'file' => ':attribute turi būti didesnis arba lygus :value kilobaitams.',
        'numeric' => ':attribute turi būti didesnis arba lygus :value.',
        'string' => ':attribute turi būti didesnis arba lygus :value simboliams.',
    ],
    'image' => ':attribute turi būti paveikslėlis.',
    'in' => 'Pasirinktas :attribute yra neteisingas.',
    'in_array' => ':attribute laukas neegzistuoja :other.',
    'integer' => ':attribute turi būti sveikasis skaičius.',
    'ip' => ':attribute turi būti teisingas IP adresas.',
    'ipv4' => ':attribute turi būti teisingas IPv4 adresas.',
    'ipv6' => ':attribute turi būti teisingas IPv6 adresas.',
    'json' => ':attribute turi būti teisingas JSON formatas.',
    'lowercase' => ':attribute turi būti mažosiomis raidėmis.',
    'lt' => [
        'array' => ':attribute turi turėti mažiau nei :value elementus.',
        'file' => ':attribute turi būti mažesnis nei :value kilobaitai.',
        'numeric' => ':attribute turi būti mažesnis nei :value.',
        'string' => ':attribute turi būti trumpesnis nei :value simboliai.',
    ],
    'lte' => [
        'array' => ':attribute neturi turėti daugiau nei :value elementus.',
        'file' => ':attribute turi būti mažesnis arba lygus :value kilobaitams.',
        'numeric' => ':attribute turi būti mažesnis arba lygus :value.',
        'string' => ':attribute turi būti mažesnis arba lygus :value simboliams.',
    ],
    'mac_address' => ':attribute turi būti teisingas MAC adresas.',
    'max' => [
        'array' => ':attribute negali turėti daugiau nei :max elementų.',
        'file' => ':attribute dydis negali būti didesnis nei :max kilobaitų.',
        'numeric' => ':attribute negali būti didesnis nei :max.',
        'string' => ':attribute negali būti ilgesnis nei :max simbolių.',
    ],
    'max_digits' => ':attribute negali turėti daugiau nei :max skaitmenų.',
    'mimes' => ':attribute turi būti failas tipo: :values.',
    'mimetypes' => ':attribute turi būti failas tipo: :values.',
    'min' => [
        'array' => ':attribute turi turėti bent :min elementus.',
        'file' => ':attribute dydis turi būti mažiausiai :min kilobaitų.',
        'numeric' => ':attribute turi būti mažiausiai :min.',
        'string' => ':attribute turi būti mažiausiai :min simboliai.',
    ],
    'min_digits' => ':attribute turi turėti bent :min skaitmenis.',
    'multiple_of' => ':attribute turi būti daugiklis :value.',
    'not_in' => 'Pasirinktas :attribute yra netinkamas.',
    'not_regex' => ':attribute formatas yra netinkamas.',
    'numeric' => ':attribute turi būti skaičius.',
    'password' => [
        'letters' => ':attribute turi turėti bent vieną raidę.',
        'mixed' => ':attribute turi turėti bent vieną didžiąją ir vieną mažąją raidę.',
        'numbers' => ':attribute turi turėti bent vieną skaičių.',
        'symbols' => ':attribute turi turėti bent vieną simbolį.',
        'uncompromised' => 'Duomenų nutekėjime buvo nurodytas :attribute. Pasirinkite kitą :attribute.',
    ],
    'present' => 'Laukas :attribute turi būti pateiktas.',
    'prohibited' => 'Laukas :attribute yra draudžiamas.',
    'prohibited_if' => 'Laukas :attribute yra draudžiamas, kai :other yra :value.',
    'prohibited_unless' => 'Laukas :attribute yra draudžiamas, nebent :other yra :values.',
    'prohibits' => 'Laukas :attribute draudžia lauką :other.',
    'regex' => 'Lauko :attribute formatas yra netinkamas.',
    'required' => 'Laukas :attribute yra privalomas.',
    'required_array_keys' => 'Lauke :attribute turi būti įrašai: :values.',
    'required_if' => 'Laukas :attribute yra privalomas, kai :other yra :value.',
    'required_if_accepted' => 'Laukas :attribute yra privalomas, kai :other yra priimtas.',
    'required_unless' => 'Laukas :attribute yra privalomas, nebent :other yra :values.',
    'required_with' => 'Laukas :attribute yra privalomas, kai yra pateikta :values reikšmė.',
    'required_with_all' => 'Laukas :attribute yra privalomas, kai yra pateiktos :values reikšmės.',
    'required_without' => 'Laukas :attribute yra privalomas, kai nėra pateikta :values reikšmė.',
    'required_without_all' => 'Laukas :attribute yra privalomas, kai nėra pateiktų :values reikšmių.',
    'same' => ':attribute ir :other turi sutapti.',
    'size' => [
    'array' => ':attribute turi turėti :size elementus.',
    'file' => ':attribute turi būti :size kilobaitų dydžio.',
    'numeric' => ':attribute turi būti :size dydžio.',
    'string' => ':attribute turi būti :size simbolių ilgio.',
    ],
    'starts_with' => ':attribute turi prasidėti viena iš šių reikšmių: :values.',
    'string' => ':attribute turi būti tekstas.',
    'timezone' => ':attribute turi būti teisinga laiko juosta.',
    'unique' => ':attribute jau yra užimtas.',
    'uploaded' => ':attribute nepavyko įkelti.',
    'uppercase' => ':attribute turi būti didžiosiomis raidėmis.',
    'url' => ':attribute turi būti teisingas URL.',
    'ulid' => ':attribute turi būti teisingas ULID.',
    'uuid' => ':attribute turi būti teisingas UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
