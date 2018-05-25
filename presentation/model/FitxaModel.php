<?php
namespace Represaliats\Presentation\Model;

class FitxaModel extends Model
{
    private $seccions = [
        'Dades personals' => [
            'Malnom' => 'malnom',
            'Edat' => 'edat',
            'Naixement' => ['dataNaixement', '%, %', 'llocNaixement.nom'],
            'Adreça' => ['carrer.nom', '%, %', 'numCarrer'],
            'Cònjuge' => ['parelles.nom', '% %', 'parelles.cognoms'],
            'Fills' => ['fills.nom', '% %', 'fills.cognoms'],
            'Pares' => ['pares.nom', '% %', 'pares.cognoms'],
            'Germans' => ['germans.nom', '% %', 'germans.cognoms'],
            'Professions' => 'oficis.nom',
            'Mort' => 'llocMort.nom',
            'Data mort' => 'dataMort',
            'Data defuncio' => 'dataDefuncio',
            'Observacions' => 'observacions',
        ],
        'Situació política i militar' => [
            'Tipus' => 'tipusSituacio.nom',
            'Causa general' => 'cg',
            'Fets d\'octubre' => ['fetsOctubre', '%, %', 'fontFetsOctubre.nom'],
            'Afiliació política' => 'partits.nom',
            'Afiliació sindical' => 'sindicats.nom',
            'Comitès' => ['comites.dataEntrada', '%  a %', 'comites.dataSortida', '%, %', 'comites.causa', '% , militant a %', 'comites.partit.nom', '%, %', 'comites.sindicat.nom'],
            'Ajuntaments' => ['ajuntaments.dataEntrada', '%  a %', 'ajuntaments.dataSortida', '%, %', 'ajuntaments.causa'],
            'Exiliat' => 'exiliat',
            'Data Estat Militar' => 'dataEstatMilitar',
            'TRP' => 'trp',
            'Data TRP' => 'dataTrp',
            'Capsa TRP' => 'capsaTrp',
            'Observacions' => 'observacionsSituacio',
        ],
        'Sumari' => [
            'Número' => 'sumari.numSumari',
            'Data inici' => 'sumari.dataInici',
            'Detenció' => ['sumari.llocDetencio.nom', '%, %', 'sumari.dataDetencio'],
            'Acusació' => 'sumari.acusacio.tipus',
            'Consell de guerra' => ['sumari.municipiConsell.nom', '%, %', 'sumari.dataConsellGuerra'],
            'Pena inicial' => ['sumari.penaInicial', '%, %', 'sumari.dataPena'],
            'Commutacions de pena' => ['sumari.commutacions.tipusCommutacio', '%  el %', 'sumari.commutacions.dataCommutacio'],
            'Llibertats' => ['sumari.llibertats.llibertat.tipus', '%  el %', 'sumari.llibertats.dataLlibertat'],
            'Execucio' => ['sumari.execucio.execucio.tipus', '%, %', 'sumari.execucio.llocExecucio.nom', '%, %', 'sumari.execucio.dataExecucio'],
            'Observacions' => 'sumari.observacions',
        ],
        'Informes' => [
            'Observacions' => 'observacionsInformes',
        ],
    ];
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getSeccions(): array {
        return $this->seccions;
    }
}