<?php

namespace DocumentacionBundle\Services;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;
use DocumentacionBundle\Entity\Documento;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use function ctype_digit;
use function dump;

/**
 * Description of DeclaracionesJuradasService
 *
 * @author esangoi
 */
class DocumentosService {

    /**
     *
     * @var EntityManager
     */
    private $em;

    /**
     *
     * @var string
     */
    private $msg;

    /**
     *
     * @var string
     */
    const ALIAS_DOC = 'd';


    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    /**
      * DocumentosService.php on line 55:
      * array:4 [▼
      *   "cuil" => "20049490489"
      *   "descripcion" => "NO"
      *   "periodoAnio" => 0
      *   "periodoMes" => 0
      * ]
     *
     * @param array $filtros
     */
    public function filtrar($filtros) {
        //Instanciar
        //dump($filtros);die;


        $qbLiq = $this->em->createQueryBuilder();
        $qbLiq->select(array(self::ALIAS_DOC));
        $qbLiq->from(Documento::class, self::ALIAS_DOC);
        $qbLiq->orderBy(self::ALIAS_DOC . '.cuil', 'ASC');
        $qbLiq->addOrderBy(self::ALIAS_DOC . '.descripcion', 'DESC');

        $andX = $qbLiq->expr()->andX(); // expresion AND en vacio
        foreach ($filtros as $filtro => $value) {
            if($value === 0){
                continue;
            }
            $prop = self::ALIAS_DOC . '.' . $filtro;
            $param = ':' . $filtro;
            $andX->add($qbLiq->expr()->eq($prop, $param));

            //Para el estado necesita el nombre del estado
                $qbLiq->setParameter($param, $value);
        }
        /**
         * Si los filtros vienen vacios no se debe agregar ninguna
         * clausula where.
         */
         if ($andX->count() > 0) {
             $qbLiq->andWhere($andX);
         }

        return $qbLiq->getQuery();
    }


}
