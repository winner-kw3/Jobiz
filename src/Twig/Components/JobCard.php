<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class JobCard
{
    public string $title;
    public string $company;
    public string $companyInitials;
    public string $location;
    public string $description;
    public string $jobType;
    public string $jobTypeClass = 'bg-teal-100 text-teal-800';
}
