<?php

namespace App;
use App\Job;
use App\User;
use App\Customer;
use App\Custid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
	use SoftDeletes;

	public function inventory_items() {
		return $this->hasMany('App\InventoryItem');
	}
	public static function prepareTagSelect(){
		return [
			0 => 'None',
			1 => 1,
			2 => 2,
			3 => 3,
			4 => 4,
			5 => 5
		];
	}

	public static function prepareQuantitySelect(){
		return [
			1 => 1,
			2 => 2,
			3 => 3,
			4 => 4,
			5 => 5,
			6 => 6,
			7 => 7,
			8 => 8,
			9 => 9,
			10 => 10
		];		
	}

	public static function prepareInventorySelect($data){
		$inventories = [];
		if(isset($data)){
			foreach ($data as $d) {
				$inventories[$d->id] = $d->name;
			}
		}

		return $inventories;

	}

	public static function prepareIconSelect(){
		$root_folder = '/img/inventory/';
		return [
			$root_folder.'bedSheets_blue.png'=>'Bed Sheets Blue',
			$root_folder.'bedSheets_green.png'=>'Bed Sheets Green',
			$root_folder.'bedSheets_red.png'=>'Bed Sheets Red',
			$root_folder.'bedSkirt_blue.png'=>'Bed Skirt Blue',
			$root_folder.'bedSkirt_green.png'=>'Bed Skirt Green',
			$root_folder.'bedSkirt_red.png'=>'Bed Skirt Red',
			$root_folder.'bedSpread_blue.png'=>'Bed Spread Blue',
			$root_folder.'bedSpread_green.png'=>'Bed Spread Green',
			$root_folder.'bedSpread_red.png'=>'Bed Spread Red',
			$root_folder.'belt.png'=>'Belt',
			$root_folder.'blanket_blue.png'=>'Blanket Blue',
			$root_folder.'blanket_green.png'=>'Blanket Green',
			$root_folder.'blanket_red.png'=>'Blanket Red',
			$root_folder.'womensBlouse.png'=>'Blouse Work',
			$root_folder.'blouse_purple.png'=>'Blouse Purple',
			$root_folder.'coatLong_brown.png'=>'Coat Long',
			$root_folder.'coatShort_gray.png'=>'Coat Medium',
			$root_folder.'comforter_blue.png'=>'Comforter Blue',
			$root_folder.'comforter_green.png'=>'Comforter Green',
			$root_folder.'comforter_red.png'=>'Comforter Red',
			$root_folder.'comforterDown_blue.png'=>'Down Comforter Blue',
			$root_folder.'comforterDown_green.png'=>'Down Comforter Green',
			$root_folder.'comforterDown_red.png'=>'Down Comforter Red',
			$root_folder.'curtain_blue.png'=>'Curtain Blue',
			$root_folder.'curtain_green.png'=>'Curtain Green',
			$root_folder.'curtain_red.png'=>'Curtain Red',
			$root_folder.'cushion_blue.png'=>'Cushion Blue',
			$root_folder.'cushion_green.png'=>'Cushion Green',
			$root_folder.'cushion_red.png'=>'Cusion Red',
			$root_folder.'dressLong_red.png'=>'Dress Long',
			$root_folder.'dressShort_pink.png'=>'Dress Short',
			$root_folder.'dressSuit_gray.png'=>'Dress 2pc',
			$root_folder.'twoPieceWomens.png'=>'Evening Dress',
			$root_folder.'duvetCover_blue.png'=>'Duvet Cover Blue',
			$root_folder.'duvetCover_green.png'=>'Duvet Cover Green',
			$root_folder.'duvetCover_red.png'=>'Duvet Cover Red',
			$root_folder.'hat.png'=>'Hat',
			$root_folder.'jacket_brown.png'=>'Jacket Brown',
			$root_folder.'jacket_gray.png'=>'Jacket Gray',
			$root_folder.'jeans.png'=>'Jeans',
			$root_folder.'labcoat_white.png'=>'Labcoat',
			$root_folder.'laundryShirt_white.png'=>'Laundry Shirt',
			$root_folder.'napkin_blue.png'=>'Napkin Blue',
			$root_folder.'napkin_green.png'=>'Napkin Green',
			$root_folder.'napkin_red.png'=>'Napkin Red',
			$root_folder.'pants_tan.png'=>'Pants Tan',
			$root_folder.'womensPants.png'=>'Pants Womans',
			$root_folder.'pantsHem_blue.png'=>'Pants Hem',
			$root_folder.'pillow_blue.png'=>'Pillow Blue',
			$root_folder.'pillow_green.png'=>'Pillow Green',
			$root_folder.'pillow_red.png'=>'Pillow Red',
			$root_folder.'pillowCase_blue.png'=>'Pillow Case Blue',
			$root_folder.'pillowCase_green.png'=>'Pillow Case Green',
			$root_folder.'pillowCase_red.png'=>'Pillow Case Red',
			$root_folder.'pillowDown_blue.png'=>'Pillow Down Blue',
			$root_folder.'pillowDown_green.png'=>'Pillow Down Green',
			$root_folder.'pillowDown_red.png'=>'Pillow Down Red',
			$root_folder.'placemat_blue.png'=>'Placemat Blue',
			$root_folder.'placemat_green.png'=>'Placemat Green',
			$root_folder.'placemat_red.png'=>'Placemat Red',
			$root_folder.'polo_red.png'=>'Polo Red',
			$root_folder.'question.png'=>'Question',
			$root_folder.'robe_brown.png'=>'Robe Brown',
			$root_folder.'rug_blue.png'=>'Rug Blue',
			$root_folder.'rug_green.png'=>'Rug Green',
			$root_folder.'rug_red.png'=>'Rug Red',
			$root_folder.'runner_blue.png'=>'Runner Blue',
			$root_folder.'runner_green.png'=>'Runner Green',
			$root_folder.'runner_red.png'=>'Runner Red',
			$root_folder.'scarf_green.png'=>'Scarf Green',
			$root_folder.'scarf_purple.png'=>'Scarf Purple',
			$root_folder.'scissors.png'=>'Scissors',
			$root_folder.'sewingMachine.png'=>'Sewing',
			$root_folder.'shirtBox_blue.png'=>'Shirt Folded',
			$root_folder.'shirtKids_green.png'=>'Shirt Green',
			$root_folder.'shirtsDC_black.png'=>'Shirt Dry Clean',
			$root_folder.'shorts_gray.png'=>'Shorts',
			$root_folder.'silkBlouse_red.png'=>'Silk Blouse Red',
			$root_folder.'skirt_green.png'=>'Skirt Green',
			$root_folder.'sleepingBag_blue.png'=>'Sleeping Bag Blue',
			$root_folder.'sleepingBag_green.png'=>'Sleeping Bag Green',
			$root_folder.'sleepingBag_red.png'=>'Sleeping Bag Red',
			$root_folder.'sportsJacket_blue.png'=>'Sports Jacket Blue',
			$root_folder.'suit_black.png'=>'Suit Mens',
			$root_folder.'sweater_orange.png'=>'Sweater Orange',
			$root_folder.'sweaterBrush_brown.png'=>'Sweater Brown',
			$root_folder.'sweaterBrushed.png'=>'Sweater Jacket',
			$root_folder.'sweaterSew_pink.png'=>'Sweater Fix Holes',
			$root_folder.'tableCloth_blue.png'=>'Table Cloth Blue',
			$root_folder.'tableCloth_green.png'=>'Table Cloth Green',
			$root_folder.'tableCloth_red.png'=>'Table Cloth Red',
			$root_folder.'tie_black.png'=>'Tie',
			$root_folder.'womensTop_green.png'=>'Top Womans',
			$root_folder.'tshirt_pink.png'=>'T-Shirt',
			$root_folder.'tuxShirt_white.png'=>'Tux Shirt',
			$root_folder.'vest_gray.png'=>'Vest',
			$root_folder.'weddingBox.png'=>'Wedding Dress Boxed',
			$root_folder.'weddingGown.png'=>'Wedding Dress'
		];
	}

	/*
	* Private methods
	*
	*/


}
