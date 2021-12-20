<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cartogram;
use Image;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }


    public function legend(Request $request, $id, $value) {
        // dd($value);

        $cartogram = Cartogram::
            with(['field', 'field.polygon', 'field.polygon.points', 'field.polygon.points.sample'])
            ->whereId($id)
            ->firstOrfail();

        $total = 0;

        $list = $cartogram->field->polygon->points;
        // dd($list->toArray());

        $graduate = Cartogram::getGraduate($value);
        // dd($graduate);

        for ($i = 0; $i < count($list); $i++) {
            $point = $list[$i];
            $val = $point->sample->result->{$value};

            // humus
            if ($value == 'humus') {
                if ($val <= 2) {
                    $graduate['<2']['subtotal'] += $val;
                } else if ($val <= 4){
                    $graduate['2-4']['subtotal'] += $val;
                } else if ($val <= 6) {
                    $graduate['4-6']['subtotal'] += $val;
                } else if ($val <= 8) {
                    $graduate['6-8']['subtotal'] += $val; 
                } else if ($val <= 10) {
                    $graduate['8-10']['subtotal'] += $val;
                } else {
                    $graduate['>10']['subtotal'] += $val;
                }
            }

            // ph
            else if ($value == 'ph') {
                if ($val < 4.6) {
                    $graduate['<4.5']['subtotal'] += $val;
                } else if ($val < 5.1){
                    $graduate['4.6-5']['subtotal'] += $val;
                } else if ($val < 5.5) {
                    $graduate['5.1-5.5']['subtotal'] += $val;
                } else if ($val < 6) {
                    $graduate['5.6-6']['subtotal'] += $val; 
                } else if ($val < 7) {
                    $graduate['6.1-7']['subtotal'] += $val;
                } else if ($val < 8) {
                    $graduate['7.1-8']['subtotal'] += $val;
                } else {
                    $graduate['>8']['subtotal'] += $val;
                }
            }

            // no3
            else if ($value == 'no3') {
                if ($val < 10) {
                    $graduate['<10']['subtotal'] += $val;
                } else if ($val >= 5 && $val < 10) {
                    $graduate['10-15']['subtotal'] += $val;
                } else if ($val >= 10 && $val < 15) {
                    $graduate['15-20']['subtotal'] += $val;
                } else {
                    $graduate['>20']['subtotal'] += $val;
                }
            }

            // no3_2
            else if ($value == 'no3_2') {
                if ($val < 5) {
                    $graduate['<5']['subtotal'] += $val;
                } else if ($val >= 5 && $val < 10) {
                    $graduate['5-10']['subtotal'] += $val;
                } else if ($val >= 10 && $val < 15) {
                    $graduate['10-15']['subtotal'] += $val;
                } else {
                    $graduate['>15']['subtotal'] += $val;
                }
            }

            // p
            else if ($value == 'p') {
                if ($val < 11) {
                    $graduate['<10']['subtotal'] += $val;
                } else if ($val >= 11 && $val < 16) {
                    $graduate['10-15']['subtotal'] += $val;
                } else if ($val >= 16 && $val < 31) {
                    $graduate['16-30']['subtotal'] += $val;
                } else if ($val >= 31 && $val < 45) {
                    $graduate['31-45']['subtotal'] += $val;
                } else if ($val >= 45 && $val < 60) {
                    $graduate['45-60']['subtotal'] += $val;
                } else {
                    $graduate['>60']['subtotal'] += $val;
                }
            }

            // k
            else if ($value == 'k') {
                if ($val < 101) {
                    $graduate['<101']['subtotal'] += $val;
                } else if ($val >= 101 && $val < 201) {
                    $graduate['101-201']['subtotal'] += $val;
                } else if ($val >= 201 && $val < 301) {
                    $graduate['201-301']['subtotal'] += $val;
                } else if ($val >= 301 && $val < 401) {
                    $graduate['301-401']['subtotal'] += $val;
                } else if ($val >= 401 && $val < 601) {
                    $graduate['401-601']['subtotal'] += $val;
                } else {
                    $graduate['>601']['subtotal'] += $val;
                }
            }

            // s
            else if ($value == 's') {
                if ($val < 6.0) {
                    $graduate['<6']['subtotal'] += $val;
                } else if ($val >= 6.0 && $val < 12.0) {
                    $graduate['6-12']['subtotal'] += $val;
                } else {
                    $graduate['>12']['subtotal'] += $val;
                }
            }

            // absorbed_sum
            else if ($value == 'absorbed_sum') {
                if ($val < 5.1) {
                    $graduate['<5']['subtotal'] += $val;
                } else if ($val >= 5.1 && $val < 10.1) {
                    $graduate['5-10']['subtotal'] += $val;
                } else if ($val >= 10.1 && $val < 15.1) {
                    $graduate['10-15']['subtotal'] += $val;
                } else if ($val >= 15.1 && $val < 20.1) {
                    $graduate['15-20']['subtotal'] += $val;
                } else if ($val >= 20.1 && $val < 30.1) {
                    $graduate['20-30']['subtotal'] += $val;
                } else {
                    $graduate['>30']['subtotal'] += $val;
                }
            } 

            // calcium
            else if ($value == 'calcium') {
                if ($val < 2.6) {
                    $graduate['<2.5']['subtotal'] += $val;
                } else if ($val >= 2.6 && $val < 5.1) {
                    $graduate['2.5-5']['subtotal'] += $val;
                } else if ($val >= 5.1 && $val < 10.1) {
                    $graduate['5-10']['subtotal'] += $val;
                } else if ($val >= 10.1 && $val < 15.1) {
                    $graduate['10-15']['subtotal'] += $val;
                } else if ($val >= 15.1 && $val < 20.0) {
                    $graduate['15-20']['subtotal'] += $val;
                } else {
                    $graduate['>20']['subtotal'] += $val;
                }
            }

            // magnesium
            else if ($value == 'magnesium') {
                if ($val < 0.5) {
                    $graduate['<0.5']['subtotal'] += $val;
                } else if ($val >= 0.6 && $val < 1.1) {
                    $graduate['0.5-1']['subtotal'] += $val;
                } else if ($val >= 1.1 && $val < 2.1) {
                    $graduate['1-2']['subtotal'] += $val;
                } else if ($val >= 2.1 && $val < 3.1) {
                    $graduate['2-3']['subtotal'] += $val;
                } else if ($val >= 3.1 && $val < 4.0) {
                    $graduate['3-4']['subtotal'] += $val;
                } else {
                    $graduate['>4']['subtotal'] += $val;
                }
            }

            // mn
            else if ($value == 'mn') {
                if ($val < 10.0) {
                    $graduate['<10']['subtotal'] += $val;
                } else if ($val >= 10.0 && $val < 20.0) {
                    $graduate['10-20']['subtotal'] += $val;
                } else {
                    $graduate['>20']['subtotal'] += $val;
                }
            }

            // zn
            else if ($value == 'zn') {
                if ($val < 2.1) {
                    $graduate['<2']['subtotal'] += $val;
                } else if ($val >= 2.1 && $val < 5.0) {
                    $graduate['2-5']['subtotal'] += $val;
                } else {
                    $graduate['>5']['subtotal'] += $val;
                }
            }

            // cu
            else if ($value == 'cu') {
                if ($val < 0.21) {
                    $graduate['<0.2']['subtotal'] += $val;
                } else if ($val >= 0.21 && $val < 0.50) {
                    $graduate['0.2-0.5']['subtotal'] += $val;
                } else {
                    $graduate['>0.5']['subtotal'] += $val;
                }
            }

            // salinity
            else if ($value == 'salinity') {
                if ($val < 2) {
                    $graduate['<2']['subtotal'] += $val;
                } else if ($val >= 2 && $val < 4) {
                    $graduate['2-4']['subtotal'] += $val;
                } else if ($val >= 4 && $val < 8) {
                    $graduate['4-8']['subtotal'] += $val;
                } else if ($val >= 8 && $val < 16) {
                    $graduate['8-16']['subtotal'] += $val;
                } else {
                    $graduate['>16']['subtotal'] += $val;
                }
            }

            // salinity_2
            else if ($value == 'salinity_2') {
                if ($val < 4) {
                    $graduate['<4']['subtotal'] += $val;
                } else if ($val >= 4 && $val < 8) {
                    $graduate['4-8']['subtotal'] += $val;
                } else if ($val >= 8 && $val < 16) {
                    $graduate['8-16']['subtotal'] += $val;
                } else if ($val >= 16 && $val < 24) {
                    $graduate['16-24']['subtotal'] += $val;
                } else {
                    $graduate['>24']['subtotal'] += $val;
                }
            }

            $total += $val;
        }

        for ($i = 0; $i < count($list); $i++) {
            $v = $value;

            if ($v == 'no3_2') $v = 'no3';
            if ($v == 'salinity_2') $v = 'salinity';

            $point = $list[$i];
            $val = $point->sample->result->{$v};
            // dd($val);

            // // humus
            // if ($value == 'humus') {
            //     if ($val <= 2) {
            //         $graduate['<2']['height'] = $graduate['<2']['subtotal'] / $total;
            //     } else if ($val <= 4){
            //         $graduate['2-4']['height'] = $graduate['2-4']['subtotal'] / $total;
            //     } else if ($val <= 6) {
            //         $graduate['6-8']['height'] = $graduate['4-6']['subtotal'] / $total;
            //     } else if ($val <= 8) {
            //         $graduate['6-8']['height'] = $graduate['6-8']['subtotal'] / $total; 
            //     } else if ($val <= 10) {
            //         $graduate['8-10']['height'] = $graduate['8-10']['subtotal'] / $total;
            //     } else {
            //         $graduate['>10']['height'] = $graduate['>10']['subtotal'] / $total;
            //     }
            // }

            // // ph
            // else if ($value == 'ph') {
            //     if ($val < 4.6) {
            //         $graduate['<4.5']['height'] = $graduate['<4.5']['subtotal'] / $total;
            //     } else if ($val < 5.1){
            //         $graduate['4.6-5']['height'] = $graduate['4.6-5']['subtotal'] / $total;
            //     } else if ($val < 5.5) {
            //         $graduate['5.1-5.5']['height'] = $graduate['5.1-5.5']['subtotal'] / $total;
            //     } else if ($val < 6) {
            //         $graduate['5.6-6']['height'] = $graduate['5.6-6']['subtotal'] / $total; 
            //     } else if ($val < 7) {
            //         $graduate['6.1-7']['height'] = $graduate['6.1-7']['subtotal'] / $total;
            //     } else if ($val < 8) {
            //         $graduate['7.1-8']['height'] = $graduate['7.1-8']['subtotal'] / $total;
            //     } else {
            //         $graduate['>8']['height'] = $graduate['>8']['subtotal'] / $total;
            //     }
            // }

            // // no3
            // else if ($value == 'no3') {
            //     if ($val < 5) {
            //         $graduate['<5']['height'] = $graduate['<5']['subtotal'] / $total;
            //     } else if ($val >= 5 && $val < 10) {
            //         $graduate['5-10']['height'] = $graduate['5-10']['subtotal'] / $total;
            //     } else if ($val >= 10 && $val < 15) {
            //         $graduate['10-15']['height'] = $graduate['10-15']['subtotal'] / $total;
            //     } else {
            //         $graduate['>15']['height'] = $graduate['>15']['subtotal'] / $total;
            //     }
            // }

            // // p
            // else if ($value == 'p') {
            //     if ($val < 11) {
            //         $graduate['<10']['height'] = $graduate['<11']['subtotal'] / $total;
            //     } else if ($val >= 11 && $val < 16) {
            //         $graduate['10-15']['height'] = $graduate['11-16']['subtotal'] / $total;
            //     } else if ($val >= 16 && $val < 31) {
            //         $graduate['16-30']['height'] = $graduate['16-31']['subtotal'] / $total;
            //     } else if ($val >= 31 && $val < 45) {
            //         $graduate['30-45']['height'] = $graduate['31-45']['subtotal'] / $total;
            //     } else if ($val >= 45 && $val < 60) {
            //         $graduate['45-60']['height'] = $graduate['45-60']['subtotal'] / $total;
            //     } else {
            //         $graduate['>60']['height'] = $graduate['>60']['subtotal'] / $total;
            //     }
            // }

            // // k
            // else if ($value == 'k') {
            //     if ($val < 101) {
            //         $graduate['<101']['height'] = $graduate['<101']['subtotal'] / $total;
            //     } else if ($val >= 101 && $val < 201) {
            //         $graduate['101-201']['height'] = $graduate['101-201']['subtotal'] / $total;
            //     } else if ($val >= 201 && $val < 301) {
            //         $graduate['201-301']['height'] = $graduate['201-301']['subtotal'] / $total;
            //     } else if ($val >= 301 && $val < 401) {
            //         $graduate['301-401']['height'] = $graduate['301-401']['subtotal'] / $total;
            //     } else if ($val >= 401 && $val < 601) {
            //         $graduate['401-601']['height'] = $graduate['401-601']['subtotal'] / $total;
            //     } else {
            //         $graduate['>601']['height'] = $graduate['>601']['subtotal'] / $total;
            //     }
            // }

            // // s
            // else if ($value == 's') {
            //     if ($val < 6.0) {
            //         $graduate['<6']['height'] = $graduate['<6']['subtotal'] / $total;
            //     } else if ($val >= 6.0 && $val < 12.0) {
            //         $graduate['6-12']['height'] = $graduate['6-12']['subtotal'] / $total;
            //     } else {
            //         $graduate['>12']['height'] = $graduate['>12']['subtotal'] / $total;
            //     }
            // }

            // // absorbed_sum
            // else if ($value == 'absorbed_sum') {
            //     if ($val < 5.1) {
            //         $graduate['<5']['height'] = $graduate['<5']['subtotal'] / $total;
            //     } else if ($val >= 5.1 && $val < 10.1) {
            //         $graduate['5-10']['height'] = $graduate['5-10']['subtotal'] / $total;
            //     } else if ($val >= 10.1 && $val < 15.1) {
            //         $graduate['10-15']['height'] = $graduate['10-15']['subtotal'] / $total;
            //     } else if ($val >= 15.1 && $val < 20.1) {
            //         $graduate['15-20']['height'] = $graduate['15-20']['subtotal'] / $total;
            //     } else if ($val >= 20.1 && $val < 30.1) {
            //         $graduate['20-30']['height'] = $graduate['20-30']['subtotal'] / $total;
            //     } else {
            //         $graduate['>30']['height'] = $graduate['>30']['subtotal'] / $total;
            //     }
            // } 

            // // calcium
            // else if ($value == 'calcium') {
            //     if ($val < 2.6) {
            //         $graduate['<2.5']['height'] = $graduate['<2.5']['subtotal'] / $total;
            //     } else if ($val >= 2.6 && $val < 5.1) {
            //         $graduate['2.5-5']['height'] = $graduate['2.5-5']['subtotal'] / $total;
            //     } else if ($val >= 5.1 && $val < 10.1) {
            //         $graduate['5-10']['height'] = $graduate['5-10']['subtotal'] / $total;
            //     } else if ($val >= 10.1 && $val < 15.1) {
            //         $graduate['10-15']['height'] = $graduate['10-15']['subtotal'] / $total;
            //     } else if ($val >= 15.1 && $val < 20.0) {
            //         $graduate['15-20']['height'] = $graduate['15-20']['subtotal'] / $total;
            //     } else {
            //         $graduate['>20']['height'] = $graduate['>20']['subtotal'] / $total;
            //     }
            // }

            // // magnesium
            // else if ($value == 'magnesium') {
            //     if ($val < 0.5) {
            //         $graduate['<0.5']['height'] = $graduate['<0.5']['subtotal'] / $total;
            //     } else if ($val >= 0.6 && $val < 1.1) {
            //         $graduate['0.5-1']['height'] = $graduate['0.5-1']['subtotal'] / $total;
            //     } else if ($val >= 1.1 && $val < 2.1) {
            //         $graduate['1-2']['height'] = $graduate['1-2']['subtotal'] / $total;
            //     } else if ($val >= 2.1 && $val < 3.1) {
            //         $graduate['2-3']['height'] = $graduate['2-3']['subtotal'] / $total;
            //     } else if ($val >= 3.1 && $val < 4.0) {
            //         $graduate['3-4']['height'] = $graduate['3-4']['subtotal'] / $total;
            //     } else {
            //         $graduate['>4']['height'] = $graduate['>4']['subtotal'] / $total;
            //     }
            // }

            // // mn
            // else if ($value == 'mn') {
            //     if ($val < 10.0) {
            //         $graduate['<10']['height'] = $graduate['<10']['subtotal'] / $total;
            //     } else if ($val >= 10.0 && $val < 20.0) {
            //         $graduate['10-20']['height'] = $graduate['10-20']['subtotal'] / $total;
            //     } else {
            //         $graduate['>20']['height'] = $graduate['>20']['subtotal'] / $total;
            //     }
            // }

            // // zn
            // else if ($value == 'zn') {
            //     if ($val < 2.1) {
            //         $graduate['<2']['height'] = $graduate['<2']['subtotal'] / $total;
            //     } else if ($val >= 2.1 && $val < 5.0) {
            //         $graduate['2-5']['height'] = $graduate['2-5']['subtotal'] / $total;
            //     } else {
            //         $graduate['>5']['height'] = $graduate['>5']['subtotal'] / $total;
            //     }
            // }

            // // cu
            // else if ($value == 'cu') {
            //     if ($val < 0.21) {
            //         $graduate['<0.2']['height'] = $graduate['<0.2']['subtotal'] / $total;
            //     } else if ($val >= 0.21 && $val < 0.50) {
            //         $graduate['0.2-0.5']['height'] = $graduate['0.2-0.5']['subtotal'] / $total;
            //     } else {
            //         $graduate['>0.5']['height'] = $graduate['>0.5']['subtotal'] / $total;
            //     }
            // }

            
            // // salinity
            // else if ($value == 'salinity') {
            //     if ($val < 2) {
            //         $graduate['<2']['height'] = $graduate['<2']['subtotal'] / $total;
            //     } else if ($val >= 2 && $val < 4) {
            //         $graduate['2-4']['height'] = $graduate['2-4']['subtotal'] / $total;
            //     } else if ($val >= 4 && $val < 8) {
            //         $graduate['4-8']['height'] = $graduate['4-8']['subtotal'] / $total;
            //     } else if ($val >= 8 && $val < 16) {
            //         $graduate['8-16']['height'] = $graduate['8-16']['subtotal'] / $total;
            //     } else {
            //         $graduate['>16']['height'] = $graduate['>16']['subtotal'] / $total;
            //     }
            // }


            // show all colors
            // humus
            if ($value == 'humus') {
                // if ($val <= 2) {
                    $graduate['<2']['height'] = 0.5;
                // } else if ($val <= 4){
                    $graduate['2-4']['height'] = 0.5;
                // } else if ($val <= 6) {
                    $graduate['4-6']['height'] = 0.5;
                // } else if ($val <= 8) {
                    $graduate['6-8']['height'] = 0.5; 
                // } else if ($val <= 10) {
                    $graduate['8-10']['height'] = 0.5;
                // } else {
                    $graduate['>10']['height'] = 0.5;
                // }
            }

            // ph
            else if ($value == 'ph') {
                // if ($val < 4.6) {
                    $graduate['<4.5']['height'] = 0.5;
                // } else if ($val < 5.1){
                    $graduate['4.6-5']['height'] = 0.5;
                // } else if ($val < 5.6) {
                    $graduate['5.1-5.5']['height'] = 0.5;
                // } else if ($val < 6)
                    $graduate['5.6-6']['height'] = 0.5; 
                // } else if ($val < 7) {
                    $graduate['6.1-7']['height'] = 0.5;
                // } else if ($val < 8) {
                    $graduate['7.1-8']['height'] = 0.5;
                // } else {
                    $graduate['>8']['height'] = 0.5;
                // }
            }

            // no3
            else if ($value == 'no3_2') {
                // if ($val < 5) {
                    $graduate['<5']['height'] = 0.5;
                // } else if ($val >= 5 && $val < 10) {
                    $graduate['5-10']['height'] = 0.5;
                // } else if ($val >= 10 && $val < 15) {
                    $graduate['10-15']['height'] = 0.5;
                // } else {
                    $graduate['>15']['height'] = 0.5;
                // }
            }

            // no3
            else if ($value == 'no3') {
                // if ($val < 5) {
                    $graduate['<10']['height'] = 0.5;
                // } else if ($val >= 5 && $val < 10) {
                    $graduate['10-15']['height'] = 0.5;
                // } else if ($val >= 10 && $val < 15) {
                    $graduate['15-20']['height'] = 0.5;
                // } else {
                    $graduate['>20']['height'] = 0.5;
                // }
            }


            // p
            else if ($value == 'p') {
                // if ($val < 11) {
                    $graduate['<10']['height'] = 0.5;
                // } else if ($val >= 11 && $val < 16) {
                    $graduate['10-15']['height'] = 0.5;
                // } else if ($val >= 16 && $val < 31) {
                    $graduate['16-30']['height'] = 0.5;
                // } else if ($val >= 31 && $val < 45) {
                    $graduate['31-45']['height'] = 0.5;
                // } else if ($val >= 45 && $val < 60) {
                    $graduate['45-60']['height'] = 0.5;
                // } else {
                    $graduate['>60']['height'] = 0.5;
                // }
            }

            // k
            else if ($value == 'k') {
                // if ($val < 101) {
                    $graduate['<101']['height'] = 0.5;
                // } else if ($val >= 101 && $val < 201) {
                    $graduate['101-201']['height'] = 0.5;
                // } else if ($val >= 201 && $val < 301) {
                    $graduate['201-301']['height'] = 0.5;
                // } else if ($val >= 301 && $val < 401) {
                    $graduate['301-401']['height'] = 0.5;
                // } else if ($val >= 401 && $val < 601) {
                    $graduate['401-601']['height'] = 0.5;
                // } else {
                    $graduate['>601']['height'] = 0.5;
                // }
            }

            // s
            else if ($value == 's') {
                // if ($val < 6.0) {
                    $graduate['<6']['height'] = 0.5;
                // } else if ($val >= 6.0 && $val < 12.0) {
                    $graduate['6-12']['height'] = 0.5;
                // } else {
                    $graduate['>12']['height'] = 0.5;
                // }
            }

            // absorbed_sum
            else if ($value == 'absorbed_sum') {
                // if ($val < 5.1) {
                    $graduate['<5']['height'] = 0.5;
                // } else if ($val >= 5.1 && $val < 10.1) {
                    $graduate['5-10']['height'] = 0.5;
                // } else if ($val >= 10.1 && $val < 15.1) {
                    $graduate['10-15']['height'] = 0.5;
                // } else if ($val >= 15.1 && $val < 20.1) {
                    $graduate['15-20']['height'] = 0.5;
                // } else if ($val >= 20.1 && $val < 30.1) {
                    $graduate['20-30']['height'] = 0.5;
                // } else {
                    $graduate['>30']['height'] = 0.5;
                // }
            } 

            // calcium
            else if ($value == 'calcium') {
                // if ($val < 2.6) {
                    $graduate['<2.5']['height'] = 0.5;
                // } else if ($val >= 2.6 && $val < 5.1) {
                    $graduate['2.5-5']['height'] = 0.5;
                // } else if ($val >= 5.1 && $val < 10.1) {
                    $graduate['5-10']['height'] = 0.5;
                // } else if ($val >= 10.1 && $val < 15.1) {
                    $graduate['10-15']['height'] = 0.5;
                // } else if ($val >= 15.1 && $val < 20.0) {
                    $graduate['15-20']['height'] = 0.5;
                // } else {
                    $graduate['>20']['height'] = 0.5;
                // }
            }

            // magnesium
            else if ($value == 'magnesium') {
                // if ($val < 0.5) {
                    $graduate['<0.5']['height'] = 0.5;
                // } else if ($val >= 0.6 && $val < 1.1) {
                    $graduate['0.5-1']['height'] = 0.5;
                // } else if ($val >= 1.1 && $val < 2.1) {
                    $graduate['1-2']['height'] = 0.5;
                // } else if ($val >= 2.1 && $val < 3.1) {
                    $graduate['2-3']['height'] = 0.5;
                // } else if ($val >= 3.1 && $val < 4.0) {
                    $graduate['3-4']['height'] = 0.5;
                // } else {
                    $graduate['>4']['height'] = 0.5;
                // }
            }

            // mn
            else if ($value == 'mn') {
                // if ($val < 10.0) {
                    $graduate['<10']['height'] = 0.5;
                // } else if ($val >= 10.0 && $val < 20.0) {
                    $graduate['10-20']['height'] = 0.5;
                // } else {
                    $graduate['>20']['height'] = 0.5;
                // }
            }

            // zn
            else if ($value == 'zn') {
                // if ($val < 2.1) {
                    $graduate['<2']['height'] = 0.5;
                // } else if ($val >= 2.1 && $val < 5.0) {
                    $graduate['2-5']['height'] = 0.5;
                // } else {
                    $graduate['>5']['height'] = 0.5;
                // }
            }

            // cu
            else if ($value == 'cu') {
                // if ($val < 0.21) {
                    $graduate['<0.2']['height'] = 0.5;
                // } else if ($val >= 0.21 && $val < 0.50) {
                    $graduate['0.2-0.5']['height'] = 0.5;
                // } else {
                    $graduate['>0.5']['height'] = 0.5;
                // }
            }

            // salinity
            else if ($value == 'salinity') {
                // if ($val < 2) {
                    $graduate['<2']['height'] = 0.5;
                // } else if ($val >= 2 && $val < 4) {
                    $graduate['2-4']['height'] = 0.5;
                // } else if ($val >= 4 && $val < 8) {
                    $graduate['4-8']['height'] = 0.5;
                // } else if ($val >= 8 && $val < 16) {
                    $graduate['8-16']['height'] = 0.5;
                // } else {
                    $graduate['>16']['height'] = 0.5;
                // }
            }

            // salinity_2
            else if ($value == 'salinity_2') {
                // if ($val < 2) {
                    $graduate['<4']['height'] = 0.5;
                // } else if ($val >= 2 && $val < 4) {
                    $graduate['4-8']['height'] = 0.5;
                // } else if ($val >= 4 && $val < 8) {
                    $graduate['8-16']['height'] = 0.5;
                // } else if ($val >= 8 && $val < 16) {
                    $graduate['16-24']['height'] = 0.5;
                // } else {
                    $graduate['>24']['height'] = 0.5;
                // }
            }
        }

        // dd($graduate);

        return view('cartograms.map_legend', compact('graduate', 'value'));
    }

    // cartogram
    public function cartogram(Request $request, $id, $value) {
        
        $cartogram = Cartogram::
            with(['field', 'field.polygon', 'field.polygon.points', 'field.polygon.points.sample'])
            ->whereId($id)
            ->firstOrfail();
        // dd($cartogram->toArray());

        // generate images
        $points = [];
        $results = [
            'humus' => [],
            'ph' => [],
            'p' => [],
            's' => [],
            'k' => [],
            'no3' => [],
            'no3_2' => [],
            'b' => [],
            'fe' => [],
            'salinity' => [],
            'salinity_2' => [],
            'absorbed_sum' => [],
            'mn' => [],
            'zn' => [],
            'cu' => [],
            'na' => [],
            'calcium' => [],
            'magnesium' => [],
        ];

        $list = $cartogram->field->polygon->points;
        // dd($list->toArray());

        for ($i = 0; $i < count($list); $i++) {
            $point = $list[$i];
            // dd($point->toArray());

            $results['humus'][$i] = $point->sample->result->humus;
            $results['ph'][$i] = $point->sample->result->ph;
            $results['p'][$i] = $point->sample->result->p;
            $results['s'][$i] = $point->sample->result->s;
            $results['k'][$i] = $point->sample->result->k;
            $results['no3'][$i] = $point->sample->result->no3;
            $results['no3_2'][$i] = $point->sample->result->no3;
            $results['b'][$i] = $point->sample->result->b;
            $results['fe'][$i] = $point->sample->result->fe;
            $results['salinity'][$i] = $point->sample->result->salinity;
            $results['salinity_2'][$i] = $point->sample->result->salinity;
            $results['absorbed_sum'][$i] = $point->sample->result->absorbed_sum;
            $results['mn'][$i] = $point->sample->result->mn;
            $results['zn'][$i] = $point->sample->result->zn;
            $results['cu'][$i] = $point->sample->result->cu;
            $results['na'][$i] = $point->sample->result->na;
            $results['calcium'][$i] = $point->sample->result->calcium;
            $results['magnesium'][$i] = $point->sample->result->magnesium;

            $points[$i] = [$point->lon, $point->lat];
        }
        // console.log(points);
        // dd($list);
        
        $markerImgs = [];
        $values = [
            'humus', 'ph', 'p', 's', 'k', 'no3', 'no3_2',
            'b',
            'fe',
            'cu',
            'zn',
            'mn',
            'na',
            'calcium',
            'magnesium',
            'salinity', 'salinity_2',
            'absorbed_sum',
        ];

        // foreach ($values as $val) {
        //     $markerImgs[$val] = [];
        // }

        // dd($markerImgs);
        
        for ($i = 0; $i < count($points); $i++) {
            $point = $points[$i];
            $pos = $i;

            $markers = [];

            for ($j = 0; $j < count($values); $j++) {
                $val = $values[$j];

                $html = view('cartograms.dot', compact('results', 'points', 'point', 'pos', 'value'))->render();
                // dd($html);
                $path = 'img/map/' . $list[$i]->id . '-' . $val . '.png';

                $markers[$val] = [
                    'path' => $path,
                    'id' => $list[$i]->id,
                ];

                // prepare
                $img = Image::make(public_path('img/map_dot2.png'));

                // write text at position x , y 
                $img->text($results[$val][$pos], 32, 32, function($font) {
                    $font->file(public_path('fonts/opensans.ttf'));
                    $font->size(20);
                    $font->align('center');
                    // $font->valign('middle');
                });

                // Save Image to Path 
                $img->save(public_path($path));

                // Browsershot::
                //     url('https://mail.ru')
                //     // html($html)
                //     // ->setIncludePath('$PATH:/opt/homebrew/bin/')
                //     // ->setNodeBinary('C:\\Program Files\\nodejs\\')
                //     // ->setNpmBinary('C:\\Users\\sanchoman\\AppData\\Roaming\\')
                //     ->windowSize(100, 100)
                //     ->hideBackground()
                //     ->greyscale()
                //     // ->waitUntilNetworkIdle()
                //     // ->noSandbox()
                //     // ->usePipe()
                //     // ->ignoreHttpsErrors()
                //     // ->timeout(500)
                //     ->save(public_path($path));
            }

            $markerImgs[$list[$i]->id] = $markers;

            // dd($markerImgs);
        }

        // dd($markerImgs);

        // dd($value);

        return view('cartograms.map_print', compact('cartogram', 'markerImgs', 'value'));
    }
}
