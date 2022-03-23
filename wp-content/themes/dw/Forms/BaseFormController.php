<?php

abstract class BaseFormController
{
    protected $data = [];

    public function __construct(array $data)
    {
        $this->data = $data;
        var_dump('data');

        $this->verifyNonce();
        var_dump('verify nonce');
        $this->sanitizeData();
        var_dump('sanitize');
        $this->validateData();
        var_dump('validate');
        $this->handle();
        var_dump('handle');
        $this->redirectWithSuccess();
        exit;
    }

    protected function verifyNonce()
    {
        $nonce = $this->data['_wpnonce'] ?? null;

        if (!$nonce && !wp_verify_nonce($nonce, $this->getNonceKey())) {
            die('Unauthorized.');
        }
    }

    abstract protected function getNonceKey(): string;

    protected function sanitizeData()
    {
        foreach ($this->getSanitizableAttributes() as $attribute => $sanitizer) {
            $sanitizer = new $sanitizer($this->data[$attribute] ?? null);

            $this->data[$attribute] = $sanitizer->getSanitizedValue();
        }
    }

    abstract protected function getSanitizableAttributes(): array;

    protected function validateData()
    {
        $errors = [];

        foreach ($this->getValidatableAttributes() as $attribute => $validators) {
            $errors = $this->validateAttribute($errors, $attribute, $validators);
        }

        if ($errors) {
            // Traitement d'erreur
            $this->redirectWithErrors($errors);
            exit;
        }
    }

    abstract protected function redirectWithErrors($errors);

    protected function validateAttribute($errors, $attribute, $validators) {
        foreach ($validators as $validator) {
            $validator = new $validator($this->data[$attribute] ?? null);

            if (!$validator->hasError()) continue;

            $errors[$attribute] = $validator->getError();

            break;
        }

        return $errors;
    }

    abstract protected function getValidatableAttributes(): array;

    abstract protected function handle();

    abstract protected function redirectWithSuccess();
}