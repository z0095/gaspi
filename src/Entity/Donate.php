<?php
namespace App\Entity;

use App\Repository\DonateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use function PHPSTORM_META\type;

#[ORM\Entity(repositoryClass: DonateRepository::class)]
class Donate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;
    
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;



    #[ORM\Column(length: 255)]
    private ?string $picture = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $pickupDateTime = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: "restaurant_id", referencedColumnName: "id", nullable: false)]
    private ?User $restaurant = null;

    #[ORM\OneToMany(mappedBy: 'donate', targetEntity: Panier::class)]
    private Collection $paniers;

    #[ORM\Column]
    private ?int $quantity = null;

    public function __construct()
    {
        $this->paniers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }


    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function getPickupDateTime(): ?\DateTimeInterface
    {
        return $this->pickupDateTime;
    }

    public function setPickupDateTime(\DateTimeInterface $pickupDateTime): static
    {
        $this->pickupDateTime = $pickupDateTime;

        return $this;
    }

    public function getRestaurant(): ?User
    {
        return $this->restaurant;
    }

    public function setRestaurant(?User $restaurant): static
    {
        $this->restaurant = $restaurant;

        return $this;
    }

    /**
     * Get the value of description
     */ 
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */ 
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Panier>
     */
    public function getPaniers(): Collection
    {
        return $this->paniers;
    }

    public function addPanier(Panier $panier): static
    {
        if (!$this->paniers->contains($panier)) {
            $this->paniers->add($panier);
            $panier->setDonate($this);
        }

        return $this;
    }

    public function removePanier(Panier $panier): static
    {
        if ($this->paniers->removeElement($panier)) {
            // set the owning side to null (unless already changed)
            if ($panier->getDonate() === $this) {
                $panier->setDonate(null);
            }
        }

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

}
