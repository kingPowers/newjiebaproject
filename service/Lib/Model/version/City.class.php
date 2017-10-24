<?php
/*
 * 省-市
 *      列表管理
 */
namespace version;

use version\Object;
class City  extends Object{
	/*
	 *	获取省份列表 
	 * */
	public  function provinceList(){
		return  M("city")->field("province_code,SUBSTR(province_name,3) as provinceName")->where("city_code=0")->order("province_code asc")->select();
	}
	/*
	 * 获取城市列表
	 * 
	 * */
	public  function  getCityList($provinceCode){
		return M("city")->field("city_name")->where("province_code='{$provinceCode}' and city_code!=0")->order("city_code asc")->select();
	}
        
        /*
         * 检查省份名称
         * @param 省份名称
         * @return boolean
         */
        public  function checkProvince($provinceName){
            return (boolean)(false!=M("city")->where("SUBSTR(province_name,3)='{$provinceName}'")->find());
        }
        /*
         * 检查城市名称
         * @param 城市名称
         * @return boolean
         */
        public  function checkCity($cityName){
            return (boolean)(false!=M("city")->where("city_name='{$cityName}'")->find());
        }
        
        
	
}
