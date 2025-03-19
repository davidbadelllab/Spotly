<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OnboardingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', Rule::in(['hotel', 'sports', 'restaurant', 'coworking'])],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255', 'regex:/^\+?[0-9\s-()]+$/'],
            'website' => ['nullable', 'url', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'logo' => ['nullable', 'string', 'regex:/^data:image\/(jpeg|png|svg\+xml);base64,/'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la empresa es obligatorio',
            'name.max' => 'El nombre no puede exceder los 255 caracteres',
            'type.required' => 'El tipo de negocio es obligatorio',
            'type.in' => 'El tipo de negocio seleccionado no es válido',
            'address.required' => 'La dirección es obligatoria',
            'address.max' => 'La dirección no puede exceder los 255 caracteres',
            'city.required' => 'La ciudad es obligatoria',
            'city.max' => 'La ciudad no puede exceder los 255 caracteres',
            'state.required' => 'La provincia/estado es obligatorio',
            'state.max' => 'La provincia/estado no puede exceder los 255 caracteres',
            'country.required' => 'El país es obligatorio',
            'country.max' => 'El país no puede exceder los 255 caracteres',
            'phone.required' => 'El teléfono es obligatorio',
            'phone.max' => 'El teléfono no puede exceder los 255 caracteres',
            'phone.regex' => 'El formato del teléfono no es válido',
            'website.url' => 'El formato de la URL no es válido',
            'website.max' => 'La URL no puede exceder los 255 caracteres',
            'description.required' => 'La descripción es obligatoria',
            'description.max' => 'La descripción no puede exceder los 1000 caracteres',
            'logo.regex' => 'El formato del logo no es válido. Debe ser una imagen en base64',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nombre de la empresa',
            'type' => 'tipo de negocio',
            'address' => 'dirección',
            'city' => 'ciudad',
            'state' => 'provincia/estado',
            'country' => 'país',
            'phone' => 'teléfono',
            'website' => 'sitio web',
            'description' => 'descripción',
            'logo' => 'logo',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Limpiar el teléfono de espacios y caracteres innecesarios
        if ($this->has('phone')) {
            $this->merge([
                'phone' => preg_replace('/\s+/', ' ', trim($this->phone)),
            ]);
        }

        // Asegurar que la URL tenga el protocolo
        if ($this->has('website') && $this->website) {
            $website = $this->website;
            if (!preg_match("~^(?:f|ht)tps?://~i", $website)) {
                $website = "https://" . $website;
            }
            $this->merge(['website' => $website]);
        }
    }
}
