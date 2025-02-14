<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Cnpj implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cnpj = preg_replace('/\D/', '', $value);

        if (strlen($cnpj) != 14 || preg_match('/(\d)\1{13}/', $cnpj)) {
            $fail("O $attribute informado não é um CNPJ válido.");
        }

        // Validação do primeiro dígito verificador
        for ($t = 12; $t < 14; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += $cnpj[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cnpj[$c] != $d) {
                $fail("O $attribute informado não é um CNPJ válido.");
            }
        }
    }
}
