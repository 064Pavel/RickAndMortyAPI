<?php

declare(strict_types=1);

namespace App\Resolver;

use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestDtoArgumentResolver implements ValueResolverInterface
{
    public function __construct(private SerializerInterface $serializer, private ValidatorInterface $validator)
    {
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return false !== strpos($argument->getType(), 'RequestDto');
    }

    /**
     * @throws ValidationException
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $content = $request->getContent();

        $model = $this->serializer->deserialize(
            $content,
            $argument->getType(),
            JsonEncoder::FORMAT,
        );

        if ($this->isPatch($request)) {
            $errors = $this->validator->validate($model, null, ['patch']);
        } else {
            $errors = $this->validator->validate($model);
        }

        if (count($errors) > 0) {
            $errorsArray = [];
            foreach ($errors as $error) {
                $errorsArray[$error->getPropertyPath()] = $error->getMessage();
            }
            throw new ValidationException($errorsArray);
        }

        return [$model];
    }

    private function isPatch(Request $request): bool
    {
        return $request->isMethod('PATCH');
    }
}
