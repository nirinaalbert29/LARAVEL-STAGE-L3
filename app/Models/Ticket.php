<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    public static function sumInterfichierTempsMensuel($id_inter, $date1, $date2)
        {
            $tickets = Ticket::orderBy('created_at')
                        ->whereBetween('created_at', [$date1, $date2])
                        ->where('intervenants_id', $id_inter)
                        ->get();
            $sumInterfileTimes = 0;
            $ticketCount = $tickets->count();

            for ($i = 1; $i < $ticketCount; $i++) {
                $dateFinTicketPrecedent = $tickets[$i - 1]->dateHeure_fin;
                $dateDebutTicketSuivant = $tickets[$i]->created_at;

                if (!empty($dateFinTicketPrecedent) && !empty($dateDebutTicketSuivant)) {
                    $tempsInterfichier = strtotime($dateDebutTicketSuivant) - strtotime($dateFinTicketPrecedent);
                    $sumInterfileTimes += $tempsInterfichier;
                }
            }

            // Conversion du temps total en format hh:mm:ss
            $tempsTotalFormatte = gmdate('H:i:s', $sumInterfileTimes);

            return $tempsTotalFormatte;
        }

        public static function sumInterfichierTempsMensuelGlobal( $date1, $date2)
        {
            $tickets = Ticket::orderBy('created_at')
                        ->whereBetween('created_at', [$date1, $date2])
                        ->get();
            $sumInterfileTimes = 0;
            $ticketCount = $tickets->count();

            for ($i = 1; $i < $ticketCount; $i++) {
                $dateFinTicketPrecedent = $tickets[$i - 1]->dateHeure_fin;
                $dateDebutTicketSuivant = $tickets[$i]->created_at;

                if (!empty($dateFinTicketPrecedent) && !empty($dateDebutTicketSuivant)) {
                    $tempsInterfichier = strtotime($dateDebutTicketSuivant) - strtotime($dateFinTicketPrecedent);
                    $sumInterfileTimes += $tempsInterfichier;
                }
            }

            // Conversion du temps total en format hh:mm:ss
            $tempsTotalFormatte = gmdate('H:i:s', $sumInterfileTimes);

            return $tempsTotalFormatte;
        }

}
