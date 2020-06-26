<?php

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_8D93D649F85E0677", columns={"username"})})
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=150, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(name="surname", type="string", length=150)
     */
    private $surname;

    /**
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(name="roles", type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(name="telephone", type="string", length=20, nullable=true)
     */
    private $telephone;

    /**
     * @ORM\Column(name="datecreate", type="datetime", nullable=true)
     */
    private $datecreate;

    /**
     * @ORM\Column(name="username", unique=true, type="string", length=180)
     */
    private $username;

    /**
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(name="active", type="integer")
     */
    private $active;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Beneficiario", mappedBy="userid", orphanRemoval=true)
     */
    private $beneficiarios;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Proveedor", mappedBy="userid", orphanRemoval=true)
     */
    private $proveedores;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Voluntario", mappedBy="userid", orphanRemoval=true)
     */
    private $voluntarios;

    /**
     * @ORM\Column(name="apiToken", unique=true, type="string", length=255, nullable=true)
     */
    private $apiToken;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Proveedor", mappedBy="userid", orphanRemoval=true)
     */
    private $proveedor;

    /**
     * @ORM\Column(name="tokenPushBots", type="string", nullable=true)
     */
    private $tokenPushBots;

    /**
     * @ORM\Column(name="idPushBots", type="string", nullable=true)
     */
    private $idPushBots;


    /**
     * @ORM\Column(name="oneSignalPlayerId", type="string", nullable=true)
     */
    private $oneSignalPlayerId;



    /**
     * @ORM\Column(name="plataforma", type="string", nullable=true)
     */
    private $plataforma;



    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DonacionUser", mappedBy="user", orphanRemoval=true)
     */
    private $donacionUser;


    /**
     * User constructor.
     */

    public function __construct()
    {
        $this->beneficiarios = new ArrayCollection();
        $this->proveedores = new ArrayCollection();
        $this->voluntarios = new ArrayCollection();

        $this->donacionUser = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    /**
     * @param string|null $telephone
     * @return User
     */
    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDatecreate(): ?\DateTimeInterface
    {
        return $this->datecreate;
    }

    /**
     * @param \DateTimeInterface $datecreate
     * @return User
     */
    public function setDatecreate(\DateTimeInterface $datecreate): self
    {
        $this->datecreate = $datecreate;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getActive(): ?int
    {
        return $this->active;
    }

    public function setActive(int $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return Collection|Beneficiario[]
     */
    public function getBeneficiarios(): Collection
    {
        return $this->beneficiarios;
    }

    public function addBeneficiario(Beneficiario $beneficiario): self
    {
        if (!$this->beneficiarios->contains($beneficiario)) {
            $this->beneficiarios[] = $beneficiario;
            $beneficiario->setUserid($this);
        }

        return $this;
    }

    public function removeBeneficiario(Beneficiario $beneficiario): self
    {
        if ($this->beneficiarios->contains($beneficiario)) {
            $this->beneficiarios->removeElement($beneficiario);
            // set the owning side to null (unless already changed)
            if ($beneficiario->getUserid() === $this) {
                $beneficiario->setUserid(null);
            }
        }

        return $this;
    }

    public function setBeneficiaro(array $beneficiarios){
        foreach ($beneficiarios as $beneficiario){
            $this->addBeneficiario($beneficiario);
        }
    }


    /**
     * @return array
     */
    public function objectToArray()
    {
        $data=array(
            'id'        => $this->getId(),
            'email'     => $this->getEmail(),
            'name'      => $this->getName(),
            'surname'   => $this->getSurname(),
            'username'  => $this->getUsername(),
            'apiToken'  => $this->getApiToken(),
            'fecha'     => ($this->getDatecreate()!=NULL)?$this->getDatecreate()->format('Y-m-d h:i:s'):null,
            'password'  => null,
            'active'    => $this->getActive(),
            'rol'       => $this->getRoles()
        );
        return $data;
    }

    /**
     * @return Collection|Proveedor[]
     */
    public function getProveedores(): Collection
    {
        return $this->proveedores;
    }

    public function addProveedore(Proveedor $proveedore): self
    {
        if (!$this->proveedores->contains($proveedore)) {
            $this->proveedores[] = $proveedore;
            $proveedore->setUserid($this);
        }

        return $this;
    }

    public function removeProveedore(Proveedor $proveedore): self
    {
        if ($this->proveedores->contains($proveedore)) {
            $this->proveedores->removeElement($proveedore);
            // set the owning side to null (unless already changed)
            if ($proveedore->getUserid() === $this) {
                $proveedore->setUserid(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Voluntario[]
     */
    public function getVoluntarios(): Collection
    {
        return $this->voluntarios;
    }

    public function addVoluntario(Voluntario $voluntario): self
    {
        if (!$this->voluntarios->contains($voluntario)) {
            $this->voluntarios[] = $voluntario;
            $voluntario->setUserid($this);
        }

        return $this;
    }

    public function removeVoluntario(Voluntario $voluntario): self
    {
        if ($this->voluntarios->contains($voluntario)) {
            $this->voluntarios->removeElement($voluntario);
            // set the owning side to null (unless already changed)
            if ($voluntario->getUserid() === $this) {
                $voluntario->setUserid(null);
            }
        }

        return $this;
    }

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(?string $apiToken): self
    {
        $this->apiToken = $apiToken;

        return $this;
    }


    public function getProveedor(){
        return $this->proveedor;
    }

    /**
     * @param Proveedor $proveedor
     * @return Proveedor
     */
    public function setProveedor(Proveedor $proveedor): self
    {
        $this->proveedor=$proveedor;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTokenPushBots()
    {
        return $this->tokenPushBots;
    }

    /**
     * @param mixed $tokenPushBots
     * @return User
     */
    public function setTokenPushBots($tokenPushBots)
    {
        $this->tokenPushBots = $tokenPushBots;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdPushBots()
    {
        return $this->idPushBots;
    }

    /**
     * @param mixed $idPushBots
     * @return User
     */
    public function setIdPushBots($idPushBots)
    {
        $this->idPushBots = $idPushBots;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOneSignalPlayerId()
    {
        return $this->oneSignalPlayerId;
    }

    /**
     * @param mixed $oneSignalPlayerId
     * @return User
     */
    public function setOneSignalPlayerId($oneSignalPlayerId)
    {
        $this->oneSignalPlayerId = $oneSignalPlayerId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlataforma()
    {
        return $this->plataforma;
    }

    /**
     * @param mixed $plataforma
     * @return User
     */
    public function setPlataforma($plataforma)
    {
        $this->plataforma = $plataforma;
        return $this;
    }

    /**
     * @param $rol
     * @return bool
     */
    private function isPermit($rol){
        if($this->getRoles()==null){
            return false;
        }
        return in_array($rol,$this->getRoles());
    }

    /**
     * @return bool
     */
    public function isAdmin(){
       return $this->isPermit('ROLE_ADMIN');
    }

    /**
     * @return bool
     */
    public function isBeneficiario(){
        return $this->isPermit('ROLE_BENEFICIARIO');
    }

    /**
     * @return bool
     */
    public function isVoluntario(){
        return $this->isPermit('ROLE_VOLUNTARIO');
    }




    public function getDonacionUser()
    {
        return $this->donacionUser;
    }

    public function setDonacionUser(?DonacionUser $donacionUser): self
    {
        $this->donacionUser = $donacionUser;

        return $this;
    }
}
