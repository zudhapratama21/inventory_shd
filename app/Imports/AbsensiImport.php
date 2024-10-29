<?php

namespace App\Imports;

use App\Models\HRD\Absensi;
use App\Models\HRD\Cuti;
use App\Models\HRD\Karyawan;
use App\Models\HRD\SettingCuti;
use Carbon\Carbon;
use DateTime;
use Maatwebsite\Excel\Concerns\ToModel;

class AbsensiImport implements ToModel
{
    protected $no=0;
    public function model(array $row)
    {
        $status = null;
        
        if ($this->no > 0) {
            $karyawan = Karyawan::where('no_emp',$row[0])->first();
            if ($karyawan) {                
                $timetanggal = DateTime::createFromFormat('d/m/Y', $row[5])->format('Y-m-d');            

                if ($row[9] == null && $row[10] == null) {
                    $day = Carbon::parse($timetanggal)->format('l');
                    if ($day == 'Saturday' || $day == 'Sunday') {
                        $status = 'weekend';
                    }else{
                        $ceklibur = SettingCuti::where('tanggal',$timetanggal)->get();
                        $cekcuti = Cuti::where('tanggal',$timetanggal)->where('karyawan_id',$karyawan->id)->first();
                        if (count($ceklibur) > 0) {
                            $status = 'cuti bersama';    
                        }elseif ($cekcuti) {                                          
                            $status = 'ijin';                        
                        }
                        else{
                            $status = 'tidak hadir';
                        }                    
                    }   
                         
                }
                elseif ($row[9] == "" && $row[10] !=="") {
                    
                    $status = 'error';
                }
                elseif ($row[9] !== null && $row[10] !== null) {
                    $day = Carbon::parse($timetanggal)->format('l');
                    if ($day == 'Saturday' || $day == 'Sunday') {                          
                        $status = 'weekend';                                          
                    }else if (Carbon::parse($row[9])->format('H:i')  > Carbon::parse('08:10')->format('H:i')) {
                        $status = 'terlambat';
                    }else{
                        $status = 'ontime';
                    }
                }
                
                else{
                   
                }
                                              
                $absensi = Absensi::create([
                    'karyawan_id' => $karyawan->id,
                    'clock_in' => $row[9],
                    'clock_out' => $row[10],
                    'work_time' => $row[17],
                    'tanggal' => Carbon::parse($timetanggal)->format('Y-m-d'),
                    'status' => $status,
                    'keterangan' =>  ''       
                ]);
            }else{
                
            }            
          
            
        }
      
                
        $this->no ++;
        return ;
    }       
}
