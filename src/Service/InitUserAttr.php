<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserAttribute;
use App\Enum\AttributeTypeEnum;
use App\Repository\AttributeCvRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Client\Provider\GoogleUser;

class InitUserAttr
{

  public function __construct(
    private EntityManagerInterface $em,
    private AttributeCvRepository $attrRep
  ) {}

  public function addMandatoryAttrs(User $user, GoogleUser $gUser)
  {
    $attrs = $this->attrRep->findBy(['isRemovable' => false]);
    
    // foreach ($attrs as $attr) {
    //   $userAttr = new UserAttribute();
    //   $userAttr->setAttribute($attr);
     
    //   $user->addUserAttribute($userAttr);

    //   $this->em->persist($user);
    // }

    // $this->em->flush();
  }
}
