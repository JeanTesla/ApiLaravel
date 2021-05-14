<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    private $employee;
    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }

    public function index()
    {
        try {
            return response(['data' => $this->employee->all()]);
        } catch (\Exception $e) {
            return response(['message' => $e], 500);
        }
    }

    public function show($id)
    {
        try {
            return response(['data' => $this->employee->find($id)]);
        } catch (\Exception $e) {
            return response(['message' => $e], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $employeeData = $request->all();
            if ($this->validateEmployee($employeeData)) {
                return response(['message' => 'Faltando atributos.'], 422);
            }
            $this->employee->create($employeeData);
            return response(['message' => 'Funcionario Cadastrado com sucesso.']);
        } catch (\Exception $e) {
            return response(['message' => $e], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $employeeData = $request->all();
            if ($this->validateEmployee($employeeData)) {
                return response(['message' => 'Faltando atributos.'], 422);
            }
            $employee = $this->employee->find($id);
            $employee->update($employeeData);
            return response(['message' => 'Funcionario Alterado com sucesso.']);
        } catch (\Exception $e) {
            return response(['message' => $e], 500);
        }
    }

    public function delete($id)
    {
        try {
            $employee = $this->employee->find($id);
            $employee->delete();
            return response(['message' => 'Funcionario Deletado com sucesso.']);
        } catch (\Exception $e) {
            return response(['message' => $e], 500);
        }
    }

    private function validateEmployee($employeeData)
    {
        $validatedData = Validator::make($employeeData, [
            'name' => 'required',
            'department' => 'required',
            'salary' => 'required'
        ]);
        return $validatedData->fails();
    }
}
