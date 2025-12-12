<?php

namespace App\Http\Controllers;

use App\DonVi;
use Illuminate\Http\Request;
use Validator;

class DonViController extends Controller
{

		public function __construct()
		{
				$this->middleware('auth');
		}
		public function index(){
			$query = DonVi::query();

			return response()->json([
				'message' => __('message.success'),
				'result' => $query->get(),
			], 200, []);
		}
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
				$info = $request->all();
				$validator = Validator::make($info, [
						'ten' => 'required|max:255',
				]);
				if ($validator->fails()) {
						return response()->json([
								'message' => __('Lỗi chưa nhập trường bắt buộc'),
								'result' => $validator->errors(),
						], 400, []);
				}
				$item = DonVi::create($info);
				return response()->json([
						'message' => __('message.success'),
						'result' => $item,
				], 200, []);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DonVi  $donVi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
				$info = $request->all();
				$validator = Validator::make($info, [
						'ten' => 'required|max:255',
				]);
				if ($validator->fails()) {
						return response()->json([
								'message' => __('Lỗi chưa nhập trường bắt buộc'),
								'result' => $validator->errors(),
						], 400, []);
				}
				$donVi = DonVi::find($id);
        $donVi = $donVi->update($info);
				return response()->json([
						'message' => __('message.success'),
						'result' => $donVi,
				], 200, []);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DonVi  $donVi
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
				$donVi = DonVi::find($id);
				$donVi->delete();
				return response()->json([
						'message' => __('message.success'),
						'result' => [],
				], 200, []);
    }
}
