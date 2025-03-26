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
                $timetanggal = DateTime::createFromFormat('d/m/Y', $row[2])->format('Y-m-d');            

                if ($row[3] == null && $row[4] == null) {
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
                elseif ($row[3] == "" && $row[4] !=="") {
                    
                    $status = 'error';
                }
                elseif ($row[3] !== null && $row[4] !== null) {
                    $day = Carbon::parse($timetanggal)->format('l');
                    if ($day == 'Saturday' || $day == 'Sunday') {                          
                        $status = 'weekend';                                          
                    }else if (Carbon::parse($row[3])->format('H:i')  > Carbon::parse('08:10')->format('H:i')) {
                        $status = 'terlambat';
                    }else{
                        $status = 'ontime';
                    }
                }
                
                else{
                   
                }
                                              
                $absensi = Absensi::create([
                    'karyawan_id' => $karyawan->id,
                    'clock_in' => $row[3],
                    'clock_out' => $row[4],
                    'work_time' => $row[2],
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
