<?php

namespace App\Entity;

use App\Repository\MEPRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MEPRepository::class)]
class MEP
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $full_name = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\Column(length: 255)]
    private ?string $political_group = null;

    #[ORM\Column]
    private ?int $mep_id = null;

    #[ORM\Column(length: 255)]
    private ?string $national_political_group = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Address = null;

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $twitter = null;

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $instagram = null;

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $website = null;

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $facebook = null;

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $address2 = null;

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $phone2 = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    public function setFullName(string $full_name): static
    {
        $this->full_name = $full_name;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getPoliticalGroup(): ?string
    {
        return $this->political_group;
    }

    public function setPoliticalGroup(string $political_group): static
    {
        $this->political_group = $political_group;

        return $this;
    }

    public function getMepId(): ?int
    {
        return $this->mep_id;
    }

    public function setMepId(int $mep_id): static
    {
        $this->mep_id = $mep_id;

        return $this;
    }

    public function getNationalPoliticalGroup(): ?string
    {
        return $this->national_political_group;
    }

    public function setNationalPoliticalGroup(string $national_political_group): static
    {
        $this->national_political_group = $national_political_group;

        return $this;
    }



    public function getAddress(): ?string
    {
        return $this->Address;
    }

    public function setAddress(?string $Address): static
    {
        $this->Address = $Address;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(string $twitter): static
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(string $instagram): static
    {
        $this->instagram = $instagram;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(string $website): static
    {
        $this->website = $website;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(string $facebook): static
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getAddress2(): ?string
    {
        return $this->address2;
    }

    public function setAddress2(string $address2): static
    {
        $this->address2 = $address2;

        return $this;
    }

    public function getPhone2(): ?string
    {
        return $this->phone2;
    }

    public function setPhone2(string $phone2): static
    {
        $this->phone2 = $phone2;

        return $this;
    }
}
